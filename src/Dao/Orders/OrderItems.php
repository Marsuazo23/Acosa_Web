<?php
namespace Dao\Orders;

use Dao\Table;

class OrderItems extends Table
{
    public static function getByOrderId(int $orderId)
    {
        $sql = "SELECT oi.orderItemId, oi.productId, oi.quantity, oi.unit_price, p.productName
                FROM order_items oi
                LEFT JOIN products p ON oi.productId = p.productId
                WHERE oi.orderId = :orderId";
        return self::obtenerRegistros($sql, ["orderId" => $orderId]);
    }

    public static function getTotalByOrderId(int $orderId)
    {
        $sql = "SELECT SUM(quantity * unit_price) AS total FROM order_items WHERE orderId = :orderId";
        $result = self::obtenerUnRegistro($sql, ["orderId" => $orderId]);
        return $result ? $result['total'] : 0;
    }

    public static function insertOrderItem(array $data)
    {
        $sql = "INSERT INTO order_items (orderId, productId, quantity, unit_price) VALUES (:orderId, :productId, :quantity, :unit_price)";
        return self::executeNonQuery($sql, $data);
    }

    public static function deleteByOrderId(int $orderId)
    {
        $sql = "DELETE FROM order_items WHERE orderId = :orderId";
        return self::executeNonQuery($sql, ["orderId" => $orderId]);
    }
}

