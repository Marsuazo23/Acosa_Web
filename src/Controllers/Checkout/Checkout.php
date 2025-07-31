<?php

namespace Controllers\Checkout;

use Controllers\PublicController;

class Checkout extends PublicController {
    public function run(): void
    {
        $viewData = [];

        /**
         * 1. Limpiar carritos caducados
         * Si hay productos que han pasado el tiempo de vida, se eliminan.
         * Se guarda un mensaje para mostrar en la vista si hubo cambios.
         */
        $cartsCleared = \Dao\Cart\Cart::clearExpiredCarts();

        if ($cartsCleared) {
            $_SESSION["flash_message"] = "Algunos productos de tu carrito caducaron por inactividad y fueron removidos.";
        }

        /**
         * 2. Obtener productos del carrito
         * - Si el usuario está logueado, se cargan los productos asociados a su cuenta.
         * - Si es un usuario anónimo, se usa un token almacenado en cookie.
         */
        if (\Utilities\Security::isLogged()) {
            $viewData["cartItems"] = \Dao\Cart\Cart::getCartProducts(\Utilities\Security::getUserId());
        } else {
            $anonToken = $_COOKIE["anoncod"] ?? \Utilities\Cart\CartFns::getAnnonCartCode();
            $viewData["cartItems"] = \Dao\Cart\Cart::getCartProductsAnon($anonToken);
        }

        /**
         * 3. Calcular subtotal, impuesto y total
         * Se aplica una tasa de impuesto del 15%.
         */
        $taxRate = 0.15;   
        $subtotal = 0;
        $taxTotal = 0;

        // Calcular subtotal sumando (precio x cantidad)
        foreach ($viewData["cartItems"] as $item) {
            $lineTotal = $item["crrprc"] * $item["crrctd"];
            $subtotal += $lineTotal;
        }

        // Calcular impuesto y total final
        $taxTotal = $subtotal * $taxRate;
        $total = $subtotal + $taxTotal;

        // Formatear valores para mostrar en la vista
        $viewData["cartSubtotal"] = number_format($subtotal, 2, '.', ',');
        $viewData["cartTax"] = number_format($taxTotal, 2, '.', ',');
        $viewData["cartTotal"] = number_format($total, 2, '.', ',');

        /*4. Formatear precios individuales para mostrar*/
        foreach ($viewData["cartItems"] as &$item) {
            $item["crrprc_display"] = number_format($item["crrprc"], 2, '.', ',');
        }
        unset($item); // Rompe la referencia

        /**
         * 5. Procesar POST (cuando el usuario hace checkout)
         * - Validar que el carrito tenga productos.
         * - Validar que el usuario esté logueado antes de continuar con PayPal.
         */
        if ($this->isPostBack()) {
            // Si el carrito está vacío
            if ($total <= 0) {
                $_SESSION["flash_message"] = "Tu carrito está vacío. Agrega productos antes de pagar.";
                \Utilities\Site::redirectTo("index.php?page=Checkout-Checkout");
                exit;
            }

            // Si no está logueado, mostrar alerta y redirigir a login
            if (!\Utilities\Security::isLogged()) {
                echo "<script>
                    alert('Debes iniciar sesión para continuar con el pago.');
                    window.location.href = 'index.php?page=Sec_Login';
                </script>";
                exit;
            }

            /**
             * 6. Crear la orden en PayPal
             * Se agregan todos los productos del carrito a la orden PayPal
             * y se redirige al usuario al enlace de aprobación.
             */
            if ($total > 0) {
            // Insertar la orden local en estado pendiente
            $orderData = [
                "usercod" => \Utilities\Security::getUserId(),
                "order_status" => "Pendiente",
                "shipping_status" => "En Camino",
                "order_date" => date("Y-m-d H:i:s")
            ];

            $orderId = \Dao\Orders\Orders::insertOrder($orderData);

            // Insertar cada item del carrito en order_items
            foreach ($viewData["cartItems"] as $item) {
                \Dao\Orders\OrderItems::insertOrderItem([
                    "orderId" => $orderId,
                    "productId" => $item["productId"],
                    "quantity" => $item["crrctd"],
                    "unit_price" => $item["crrprc"]
                ]);
            }

            // Guardar el orderId local en sesión para usarlo luego
            $_SESSION["local_order_id"] = $orderId;

            // Crear objeto de orden PayPal
            $PayPalOrder = new \Utilities\Paypal\PayPalOrder(
                "order_" . time(),
                "http://localhost/acosa/acosa/index.php?page=checkout_error",
                "http://localhost/acosa/acosa/index.php?page=checkout_accept"
            );

            // Agregar los productos a la orden PayPal
            foreach ($viewData["cartItems"] as $item) {
                $tax = round($item["crrprc"] * 0.15, 2); 

                $PayPalOrder->addItem(
                    $item["productName"],
                    $item["productDescription"],
                    $item["productId"],
                    $item["crrprc"],
                    $tax,
                    $item["crrctd"],
                    "PHYSICAL_GOODS"
                );
            }

            // Continúa con creación de orden PayPal y redirección
            $clientId = \Utilities\Context::getContextByKey("PAYPAL_CLIENT_ID");
            $clientSecret = \Utilities\Context::getContextByKey("PAYPAL_CLIENT_SECRET");

            $PayPalRestApi = new \Utilities\PayPal\PayPalRestApi($clientId, $clientSecret);
            $PayPalRestApi->getAccessToken();

            $response = $PayPalRestApi->createOrder($PayPalOrder);

            $_SESSION["orderid"] = $response->id;

            foreach ($response->links as $link) {
                if ($link->rel == "approve") {
                    \Utilities\Site::redirectTo($link->href);
                }
            }
            die();
        }

        }

        /* 7. Mostrar mensaje si hubo productos eliminados */
        if (isset($_SESSION["flash_message"])) {
            $viewData["cartMessage"] = $_SESSION["flash_message"];
            unset($_SESSION["flash_message"]);
        }

        /* 8. Renderizar la vista del checkout con toda la información calculada */
        \Views\Renderer::render("paypal/checkout", $viewData);
    }
}