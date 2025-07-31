<?php

namespace Controllers\Checkout;

use Controllers\PublicController;

class Error extends PublicController
{
    public function run(): void
    {
        // Si existe una orden local en sesión, eliminarla
        if (isset($_SESSION["local_order_id"])) {
            $orderId = $_SESSION["local_order_id"];

            // Eliminar items de la orden
            \Dao\Orders\OrderItems::deleteByOrderId($orderId);

            // Eliminar la orden principal
            \Dao\Orders\Orders::deleteOrder($orderId);

            // Limpiar variable de sesión
            unset($_SESSION["local_order_id"]);
        }

        // Mensaje flash
        $_SESSION["flash_message"] = "Has cancelado el pago. El carrito sigue intacto.";

        // Redirigir a carrito
        \Utilities\Site::redirectTo("index.php?page=Checkout-Checkout");
        exit;
    }
}

?>
