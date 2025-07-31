<?php
namespace Utilities\Cart;

class CartFns
{
    // Tiempo máximo para carritos de usuarios logueados: 6 horas
    public static function getAuthTimeDelta()
    {
        return 21600; // 6 * 60 * 60
    }

    // Tiempo máximo para carritos de invitados: 10 minutos
    public static function getUnAuthTimeDelta()
    {
        return 600; // 10 * 60
    }

    // Genera o devuelve un código único persistente para carrito anónimo
    public static function getAnnonCartCode()
    {
        if (isset($_SESSION["annonCartCode"])) {
            return $_SESSION["annonCartCode"];
        }

        // Genera un token único de 128 caracteres
        $_SESSION["annonCartCode"] = substr(
            md5("cart2025" . time() . random_int(10000, 99999)),
            0,
            128
        );

        // Guarda en cookie para persistir aunque cierre el navegador
        setcookie("anoncod", $_SESSION["annonCartCode"], time() + 60 * 60 * 24 * 30, "/");

        return $_SESSION["annonCartCode"];
    }
}
