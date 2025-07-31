<?php
namespace Controllers\Checkout;

use Controllers\PublicController;
use Dao\Cart\Cart;

class UpdateQuantity extends PublicController
{
    public function run(): void
    {
        /**
         * 1. Validar que la solicitud sea POST
         * Si no es POST, redirige al checkout para evitar acceso directo por URL.
         */
        if (!$this->isPostBack()) {
            header("Location: index.php?page=Checkout-Checkout");
            exit;
        }

        /**
         * 2. Obtener los datos enviados por POST
         * - productId: ID del producto a modificar.
         * - action: acción a realizar ("increase" o "decrease").
         */
        $productId = $_POST["productId"] ?? null;
        $action = $_POST["action"] ?? null;

        /**
         * 3. Validar que los datos sean correctos
         * - Debe existir productId y la acción debe ser válida.
         */
        if (!$productId || !in_array($action, ["increase", "decrease"])) {
            header("Location: index.php?page=Checkout-Checkout");
            exit;
        }

        /**
         * 4. Obtener identificadores del usuario
         * - Si está logueado, se usa su ID.
         * - Si es anónimo, se usa el token almacenado en cookie.
         */
        $userId = \Utilities\Security::getUserId();
        $isLogged = \Utilities\Security::isLogged();
        $anonId = $_COOKIE["anoncod"] ?? \Utilities\Cart\CartFns::getAnnonCartCode();

        /**
         * 5. Obtener el carrito actual
         * - Dependiendo si es usuario logueado o anónimo.
         */
        $cartItems = $isLogged
            ? Cart::getCartProducts($userId)
            : Cart::getCartProductsAnon($anonId);

        /* 6. Buscar el producto específico dentro del carrito */
        $item = array_filter($cartItems, fn($i) => $i["productId"] == $productId);
        $item = reset($item); // Tomar el primer elemento del resultado

        // Si no se encuentra el producto, redirigir al checkout
        if (!$item) {
            header("Location: index.php?page=Checkout-Checkout");
            exit;
        }

        /**
         * 7. Calcular la nueva cantidad según la acción
         * - Si la acción es "increase", sumamos 1.
         * - Si la acción es "decrease", restamos 1 pero nunca bajamos de 1.
         */
        $currentQty = $item["crrctd"];
        if ($action === "increase") {
            $newQty = $currentQty + 1;
        } else {
            $newQty = max(1, $currentQty - 1); // Evita bajar de 1
        }

        /**
         * 8. Actualizar la cantidad en la base de datos
         * - Según si el usuario está logueado o es anónimo.
         */
        if ($isLogged) {
            Cart::updateQuantity($userId, $productId, $newQty);
        } else {
            Cart::updateQuantityAnon($anonId, $productId, $newQty);
        }

        /* 9. Redirigir al checkout para mostrar los cambios */
        header("Location: index.php?page=Checkout-Checkout");
    }
}
