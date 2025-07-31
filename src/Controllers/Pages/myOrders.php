<?php
namespace Controllers\Pages;

use Controllers\PublicController;
use Dao\Orders\Orders;
use Dao\Orders\OrderItems;
use Utilities\Security;
use Views\Renderer;
use Utilities\Site;

class myOrders extends PublicController
{
    public function run(): void
    {
        $usercod = Security::getUserId();

        if (!$usercod) {
            Site::redirectTo("index.php");
            return;
        }

        // Trae órdenes
        $orders = Orders::getByUserId($usercod);

        // Calcular total de cada orden
        foreach ($orders as &$order) {
            $subtotal = OrderItems::getTotalByOrderId($order['orderId']);
            $tax = round($subtotal * 0.15, 2);
            $total = $subtotal + $tax;

            // Formatear el total con coma de miles y 2 decimales
            $order['total_amount'] = number_format($total, 2, '.', ',');
            $order['currency'] = "LPS";
        }


        Renderer::render("pages/orders", ["orders" => $orders]);
    }
}
