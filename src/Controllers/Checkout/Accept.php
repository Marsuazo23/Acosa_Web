<?php

namespace Controllers\Checkout;

use Controllers\PublicController;

class Accept extends PublicController
{
    public function run(): void
    {
        $dataview = array();

        // Se obtiene el token enviado por PayPal vía GET
        $token = $_GET["token"] ?? "";

        // Se obtiene el token almacenado en la sesión (orderid)
        $session_token = $_SESSION["orderid"] ?? "";

        // Verifica que el token recibido sea válido y coincida con el de la sesión
        if ($token !== "" && $token == $session_token) {
            // Obtiene los detalles de la orden en PayPal
            $orderDetails = \Utilities\Paypal\PayPalCapture::getOrderDetails($session_token);

            // Si el estado ya está COMPLETADO significa que la orden ya se capturó
            if ($orderDetails->result->status === 'COMPLETED') {
                // Guarda los detalles en JSON para mostrarlos en la vista
                $dataview["orderjson"] = json_encode($orderDetails, JSON_PRETTY_PRINT);

                // Guarda la transacción en la base de datos si no está registrada
                $this->savePaypalTransaction($orderDetails);
                
                // Limpia el carrito del usuario
                $this->clearUserCart();

            } else {
                // Si la orden no está completada, se intenta capturar el pago
                $result = \Utilities\Paypal\PayPalCapture::captureOrder($session_token);

                // Guarda los detalles de la captura en formato JSON
                $dataview["orderjson"] = json_encode($result, JSON_PRETTY_PRINT);

                // Si la captura se completó correctamente
                if ($result->result->status === 'COMPLETED') {
                    // Guarda la transacción en la base de datos
                    $this->savePaypalTransaction($result);

                    // Limpia el carrito del usuario
                    $this->clearUserCart();
                }
            }
        } else {
            // Si no hay token válido, muestra un mensaje de error
            $dataview["orderjson"] = "No Order Available!!!";
        }

        // Renderiza la vista con los datos
        \Views\Renderer::render("paypal/accept", $dataview);
    }

    /*Guarda la transacción de PayPal en la base de datos*/
    private function savePaypalTransaction($paypalResponse)
    {
        // Se extraen los datos principales de la respuesta de PayPal
        $orderId = $paypalResponse->result->id ?? '';
        $status = $paypalResponse->result->status ?? '';
        $purchaseUnits = $paypalResponse->result->purchase_units ?? [];

        $captureId = '';
        $amount = 0;
        $currency = '';
        $paypalFee = 0;
        $netAmount = 0;

        // Datos del pagador
        $payerEmail = $paypalResponse->result->payer->email_address ?? '';
        $payerName = trim(
            ($paypalResponse->result->payer->name->given_name ?? '') . ' ' .
            ($paypalResponse->result->payer->name->surname ?? '')
        );
        $payerId = $paypalResponse->result->payer->payer_id ?? '';
        $payerCountry = $paypalResponse->result->payer->address->country_code ?? '';
        $shippingAddress = null;

        // Obtiene datos de la captura (primer purchase_unit y su primer capture)
        if (!empty($purchaseUnits)) {
            $payments = $purchaseUnits[0]->payments ?? null;

            // Si existen capturas, se toma la primera
            if ($payments && !empty($payments->captures)) {
                $capture = $payments->captures[0];
                $captureId = $capture->id ?? '';
                $amount = $capture->amount->value ?? 0;
                $currency = $capture->amount->currency_code ?? '';

                // Si PayPal envía breakdown, se obtiene comisión y monto neto
                if (isset($capture->seller_receivable_breakdown)) {
                    $paypalFee = $capture->seller_receivable_breakdown->paypal_fee->value ?? 0;
                    $netAmount = $capture->seller_receivable_breakdown->net_amount->value ?? 0;
                }
            }

            // Si existe dirección de envío, se guarda como JSON
            if (isset($purchaseUnits[0]->shipping->address)) {
                $shippingAddress = json_encode($purchaseUnits[0]->shipping->address);
            }
        }

        // Se obtiene el ID del usuario logueado
        $usercod = \Utilities\Security::getUserId() ?? null;

        // Evita guardar la transacción si ya existe en la base de datos
        $exists = \Dao\Paypal\PaypalTransactions::existsByOrderId($orderId);
        if ($exists) {
            return; 
        }

        // Inserta la transacción en la base de datos
        $paypalTransactionId = \Dao\Paypal\PaypalTransactions::insertTransaction([
            "order_id" => $orderId,
            "capture_id" => $captureId,
            "status" => $status,
            "amount" => $amount,
            "currency" => $currency,
            "paypal_fee" => $paypalFee,
            "net_amount" => $netAmount,
            "payer_email" => $payerEmail,
            "payer_name" => $payerName,
            "payer_id" => $payerId,
            "payer_country" => $payerCountry,
            "shipping_address" => $shippingAddress,
            "usercod" => $usercod
        ]);

        // Recuperar el orderId local guardado en sesión
        $localOrderId = $_SESSION["local_order_id"] ?? null;

            if ($localOrderId && $paypalTransactionId) {
                \Dao\Orders\Orders::updatePaypalTransaction($localOrderId, intval($paypalTransactionId));
            }
        }

    /*Limpia el carrito del usuario después de completar la compra*/
    private function clearUserCart()
    {
        $usercod = \Utilities\Security::getUserId() ?? null;

        if ($usercod) {
            // Obtener productos del carrito
            $cartItems = \Dao\Cart\Cart::getCartProducts($usercod);

            // Reducir stock por cada producto
            foreach ($cartItems as $item) {
                \Dao\Cart\Cart::reduceStock($item["productId"], $item["crrctd"]);
            }

            // Limpiar carrito
            \Dao\Cart\Cart::clearCartByUser($usercod);
        } else {
            $anoncod = $_COOKIE["anoncod"] ?? null;
            if ($anoncod) {
                // Obtener productos del carrito anónimo
                $cartItems = \Dao\Cart\Cart::getCartProductsAnon($anoncod);

                // Reducir stock por cada producto
                foreach ($cartItems as $item) {
                    \Dao\Cart\Cart::reduceStock($item["productId"], $item["crrctd"]);
                }

                // Limpiar carrito
                \Dao\Cart\Cart::clearCartByAnon($anoncod);
            }
        }
    }
}

?>