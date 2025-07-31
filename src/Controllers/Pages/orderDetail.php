<?php
namespace Controllers\Pages;

use Controllers\PublicController;
use Dao\Orders\Orders;
use Dao\Orders\OrderItems;
use Utilities\Security;
use Views\Renderer;
use Utilities\Site;

class orderDetail extends PublicController
{
    public function run(): void
    {
        Site::addLink("public/css/pages/orderDetail.css");

        $usercod = Security::getUserId();

        if (!$usercod) {
            Site::redirectTo("index.php");
            return;
        }

        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            Site::redirectTo("index.php?page=Pages-myOrders");
            return;
        }

        $order = Orders::getById($orderId);

        if (!$order || $order['usercod'] != $usercod) {
            Site::redirectTo("index.php?page=Pages-myOrders");
            return;
        }

        $items = OrderItems::getByOrderId($orderId);

        $total = 0;
        foreach ($items as &$item) {
            $item['subtotal'] = $item['quantity'] * $item['unit_price'];

            // Formatear unit_price y subtotal
            $item['unit_price'] = number_format($item['unit_price'], 2, '.', ',');
            $item['subtotal'] = number_format($item['subtotal'], 2, '.', ',');

            $total += $item['quantity'] * $item['unit_price'];
        }
        unset($item);

        // Calcular impuesto 15% y total final
        $tax = round($total * 0.15, 2);
        $grandTotal = $total + $tax;

        // Formatear subtotal, impuesto y total
        $totalFormatted = number_format($total, 2, '.', ',');
        $taxFormatted = number_format($tax, 2, '.', ',');
        $grandTotalFormatted = number_format($grandTotal, 2, '.', ',');

        Renderer::render("pages/orderDetail", [
            "orderId" => $order['orderId'],
            "order_status" => $order['order_status'],
            "shipping_status" => $order['shipping_status'], 
            "order_date" => $order['order_date'],
            "subtotal_amount" => $totalFormatted,
            "tax_amount" => $taxFormatted,
            "total_amount" => $grandTotalFormatted,
            "currency" => "LPS",
            "items" => $items,
        ]);
    }
}
