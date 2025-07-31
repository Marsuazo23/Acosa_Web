<?php

namespace Dao\Paypal;

use Dao\Table;

class PaypalTransactions extends Table
{
    public static function existsByOrderId(string $orderId): bool
    {
        $sql = "SELECT COUNT(*) as cnt FROM paypal_transactions WHERE order_id = :order_id";
        $row = self::obtenerUnRegistro($sql, ["order_id" => $orderId]);
        return $row && $row["cnt"] > 0;
    }

    public static function insertTransaction(array $data)
    {
        $sql = "INSERT INTO paypal_transactions 
            (order_id, capture_id, status, amount, currency, paypal_fee, net_amount, payer_email, payer_name, payer_id, payer_country, shipping_address, usercod)
            VALUES
            (:order_id, :capture_id, :status, :amount, :currency, :paypal_fee, :net_amount, :payer_email, :payer_name, :payer_id, :payer_country, :shipping_address, :usercod)";

        self::executeNonQuery($sql, [
            "order_id" => $data["order_id"],
            "capture_id" => $data["capture_id"],
            "status" => $data["status"],
            "amount" => $data["amount"],
            "currency" => $data["currency"],
            "paypal_fee" => $data["paypal_fee"] ?? null,
            "net_amount" => $data["net_amount"] ?? null,
            "payer_email" => $data["payer_email"],
            "payer_name" => $data["payer_name"],
            "payer_id" => $data["payer_id"] ?? null,
            "payer_country" => $data["payer_country"] ?? null,
            "shipping_address" => $data["shipping_address"] ?? null,
            "usercod" => $data["usercod"] ?? null
        ]);

        return self::getLastInsertId();
    }

    public static function getAllTransactions()
    {
        $sql = "SELECT * FROM paypal_transactions ORDER BY created_at DESC"; // Ajusta el nombre del campo fecha si es distinto
        return self::obtenerRegistros($sql, []);
    }

    public static function getById(int $id)
    {
        $sql = "SELECT * FROM paypal_transactions WHERE id_transaction = :id";
        return self::obtenerUnRegistro($sql, ["id" => $id]);
    }
}

?>