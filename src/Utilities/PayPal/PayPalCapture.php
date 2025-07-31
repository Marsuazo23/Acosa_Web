<?php
namespace Utilities\Paypal;

use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PayPalCapture
{
    // Captura una orden de PayPal previamente creada y aprobada.
    public static function captureOrder($orderId)
    {
        // Crear una solicitud de captura usando el ID de la orden
        $request = new OrdersCaptureRequest($orderId);

        // Inicializar el cliente de PayPal configurado en PayPalClient
        $client = PayPalClient::client();

        // Ejecutar la solicitud contra la API de PayPal
        $response = $client->execute($request);

        // Retornar la respuesta de la API
        return $response;
    }

    // Obtiene los detalles de una orden de PayPal sin capturarla.
    public static function getOrderDetails($orderId)
    {
        // Crear solicitud para obtener detalles de la orden
        $request = new OrdersGetRequest($orderId);

        // Inicializar cliente PayPal
        $client = PayPalClient::client();

        // Ejecutar la solicitud a la API
        $response = $client->execute($request);

        // Retornar la información de la orden
        return $response;
    }
}

?>
