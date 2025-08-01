<?php
namespace Controllers\Checkout;

use Controllers\PublicController;
use Dao\Cart\Cart;

class AddToCart extends PublicController
{
    public function run(): void
    {
        // Validar que la solicitud sea POST; si no, devolver error 405 (Método no permitido)
        if (!$this->isPostBack()) {
            http_response_code(405);
            echo json_encode(["error" => "Método no permitido"]);
            return;
        }

        // Obtener el ID del producto y la cantidad enviados por POST
        $productId = $_POST["productId"] ?? null;
        $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;

        // Validar que haya un ID de producto y que la cantidad sea mayor a 0
        if (!$productId || $quantity < 1) {
            http_response_code(400);
            echo json_encode(["error" => "Datos inválidos"]);
            return;
        }

        // Obtener el ID del usuario (si está logueado)
        $userId = \Utilities\Security::getUserId();

        // Variable para guardar resultado de agregar
        $result = false;
        
        // Si el usuario está autenticado, agregar el producto a su carrito de usuario
        if (\Utilities\Security::isLogged()) {
        $result = Cart::addToCarretilla($userId, $productId, $quantity);
        } else {
            // Si no está autenticado, usar el carrito anónimo
            // Si no existe cookie anónima, se genera un nuevo código de carrito
            $anonId = $_COOKIE["anoncod"] ?? \Utilities\Cart\CartFns::getAnnonCartCode();
            $result = Cart::addToCarretillaAnon($anonId, $productId, $quantity);
        }
        if (!$result) {
            // Si la función devuelve false, significa que no se pudo agregar por stock insuficiente
            http_response_code(409); // Conflict
            echo json_encode(["error" => "No hay suficiente stock disponible para agregar la cantidad solicitada."]);
            return;
        }

        // Obtener el nuevo total de artículos en el carrito y devolverlo como JSON
        $count = Cart::getCartCount($userId);
        echo json_encode(["success" => true, "cartCount" => $count]);
    }
}
