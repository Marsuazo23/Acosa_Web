<?php
namespace Dao\Orders;

use Dao\Table;

class Orders extends Table
{
    public static function getAll()
    {
        $sql = "SELECT o.orderId, 
                    o.usercod, 
                    o.order_status, 
                    o.shipping_status,
                    o.order_date,
                    u.userName,
                    pt.currency
                FROM orders o
                LEFT JOIN usuario u ON o.usercod = u.usercod
                LEFT JOIN paypal_transactions pt ON o.transaction_id = pt.id_transaction
                ORDER BY o.order_date ASC";
        return self::obtenerRegistros($sql, []);
    }

    public static function getByUserId(int $usercod)
    {
        $sql = "SELECT o.orderId, o.usercod, o.order_status, o.order_date, o.shipping_status, pt.currency
                FROM orders o
                LEFT JOIN paypal_transactions pt ON o.transaction_id = pt.id_transaction
                WHERE o.usercod = :usercod
                ORDER BY o.order_date ASC";
        return self::obtenerRegistros($sql, ["usercod" => $usercod]);
    }

    public static function getByOrderId(int $orderId)
    {
        $sql = "SELECT orderItemId, productId, quantity, unit_price FROM order_items WHERE orderId = :orderId";
        return self::obtenerRegistros($sql, ["orderId" => $orderId]);
    }

    public static function insertOrder(array $data)
    {
        $sql = "INSERT INTO orders (usercod, order_status, shipping_status, order_date) 
                VALUES (:usercod, :order_status, :shipping_status, :order_date)";
        self::executeNonQuery($sql, $data);
        return self::getLastInsertId(); 
    }

    public static function updatePaypalTransaction(int $orderId, int $paypalTransactionId)
    {
        $sql = "UPDATE orders SET transaction_id = :transaction_id, order_status = 'Pagado' WHERE orderId = :orderId";
        return self::executeNonQuery($sql, [
            "transaction_id" => $paypalTransactionId,
            "orderId" => $orderId
        ]);
    }

    public static function getById(int $orderId)
    {
        $sql = "SELECT o.orderId, o.usercod, o.order_status, o.shipping_status, o.order_date, u.userName, pt.currency
                FROM orders o
                LEFT JOIN usuario u ON o.usercod = u.usercod
                LEFT JOIN paypal_transactions pt ON o.transaction_id = pt.id_transaction
                WHERE o.orderId = :orderId";
        return self::obtenerUnRegistro($sql, ["orderId" => $orderId]);
    }
    
    public static function updateShippingStatus($orderId, $shipping_status)
    {
        $sql = "UPDATE orders SET shipping_status = :shipping_status WHERE orderId = :orderId";
        return self::executeNonQuery($sql, [
            "orderId" => $orderId,
            "shipping_status" => $shipping_status
        ]);
    }

    public static function deleteOrder(int $orderId)
    {
        $sql = "DELETE FROM orders WHERE orderId = :orderId";
        return self::executeNonQuery($sql, ["orderId" => $orderId]);
    }
}
