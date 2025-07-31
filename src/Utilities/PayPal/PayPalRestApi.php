<?php

namespace Utilities\PayPal;

use Utilities\Paypal\PayPalOrder;

class PayPalRestApi
{
    private $_baseUrl;
    private $_clientId;
    private $_clientSecret;
    private $_token;
    private $_tokenExpiration;
    private $_tokenType;
    private $_tokenScope;
    private $_tokenAppId;
    private $_tokenNonce;

    // Constructor que define credenciales y entorno (sandbox o live)
    public function __construct(string $clientId, string $clientSecret, $envrioment = "sandbox")
    {
        var_dump($clientId, $clientSecret, $envrioment); // Para debug, quitar en producción

        $this->_clientId = $clientId;
        $this->_clientSecret = $clientSecret;

        // Definir URL base según entorno
        if ($envrioment == "sandbox") {
            $this->_baseUrl = "https://api-m.sandbox.paypal.com";
        } else {
            $this->_baseUrl = "https://api-m.paypal.com";
        }
    }

    // Obtiene el token de acceso; solicita uno nuevo si no existe o expiró
    public function getAccessToken()
    {
        if ($this->_token == null || $this->_tokenExpiration < time()) {
            $this->requestAccessToken();
        }
        return $this->_token;
    }

    // Solicita un token de acceso a PayPal usando clientId y clientSecret
    private function requestAccessToken()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->_baseUrl . "/v1/oauth2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_USERPWD => $this->_clientId . ":" . $this->_clientSecret,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);

        $this->_token = $response->access_token;
        $this->_tokenExpiration = time() + $response->expires_in;
        $this->_tokenType = $response->token_type;
        $this->_tokenScope = $response->scope;
        $this->_tokenAppId = $response->app_id;
        $this->_tokenNonce = $response->nonce;
    }

    // Crea una orden en PayPal a partir del objeto PayPalOrder
    public function createOrder(PayPalOrder $order)
    {
        $jsonBody = json_encode($order->getOrder(), JSON_PRETTY_PRINT);
        error_log("PayPal createOrder JSON: " . $jsonBody);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->_baseUrl . "/v2/checkout/orders",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $jsonBody,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $this->getAccessToken()
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    // Captura una orden aprobada por PayPal para completar el pago
    public function captureOrder($orderId)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->_baseUrl . "/v2/checkout/orders/" . $orderId . "/capture",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $this->getAccessToken()
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }
}
