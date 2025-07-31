<?php
namespace Controllers\Checkout;

use Controllers\PublicController;
use Dao\Cart\Cart;

class RemoveFromCart extends PublicController
{
    public function run(): void
    {
        /**
         * 1. Validar que la solicitud sea POST
         * - Si no es POST, devolver error 405 (Método no permitido)
         */
        if (!$this->isPostBack()) {
            http_response_code(405);
            echo json_encode(["error" => "Método no permitido"]);
            return;
        }

        /* 2. Obtener el ID del producto desde POST */
        $productId = $_POST["productId"] ?? null;

        /**
         * 3. Validar que el ID del producto sea válido
         * - Si no se envía productId, devolver error 400
         */
        if (!$productId) {
            http_response_code(400);
            echo json_encode(["error" => "Producto inválido"]);
            return;
        }

        /**
         * 4. Determinar si el usuario está logueado o es anónimo
         * - Si está logueado: usar su userId para eliminar el producto de su carrito
         * - Si es anónimo: usar el código del carrito almacenado en cookie
         */
        if (\Utilities\Security::isLogged()) {
            $userId = \Utilities\Security::getUserId();
            Cart::removeFromCarretilla($userId, $productId);
        } else {
            $anonId = $_COOKIE["anoncod"] ?? \Utilities\Cart\CartFns::getAnnonCartCode();
            Cart::removeFromCarretillaAnon($anonId, $productId);
        }

        /* 5. Guardar mensaje en sesión para mostrarlo en el checkout */
        $_SESSION["flash_message"] = "Producto eliminado del carrito";

        /**
         * 6. Redirigir al checkout
         * - Después de eliminar el producto, recarga la vista del carrito actualizada
         */
        \Utilities\Site::redirectTo("index.php?page=Checkout-Checkout");
    }
}
