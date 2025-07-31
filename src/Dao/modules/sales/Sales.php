<?php

namespace Dao\modules\sales;

use Dao\Table;

class Sales extends Table
{
    public static function getAll(): array
    {
        $sqlstr = "SELECT saleId, productId, discountPercent, saleStart, saleEnd FROM sales;";
        return self::obtenerRegistros($sqlstr, []);
    }

    public static function getById(int $id)
    {
        $sqlstr = "SELECT * FROM sales WHERE saleId = :saleId;";
        return self::obtenerUnRegistro($sqlstr, ["saleId" => $id]);
    }

    public static function insertSale(
        int $productId,
        float $discountPercent,
        string $saleStart,
        string $saleEnd
    ) {
        $sqlstr = "INSERT INTO sales (productId, discountPercent, saleStart, saleEnd)
                VALUES (:productId, :discountPercent, :saleStart, :saleEnd);";
        return self::executeNonQuery(
            $sqlstr,
            [
                "productId" => $productId,
                "discountPercent" => $discountPercent,
                "saleStart" => $saleStart,
                "saleEnd" => $saleEnd
            ]
        );
    }

    public static function updateSale(
        int $id,
        int $productId,
        float $discountPercent,
        string $saleStart,
        string $saleEnd
    ) {
        $sqlstr = "UPDATE sales SET 
                    productId = :productId,
                    discountPercent = :discountPercent,
                    saleStart = :saleStart,
                    saleEnd = :saleEnd
                WHERE saleId = :saleId;";
        return self::executeNonQuery(
            $sqlstr,
            [
                "productId" => $productId,
                "discountPercent" => $discountPercent,
                "saleStart" => $saleStart,
                "saleEnd" => $saleEnd,
                "saleId" => $id
            ]
        );
    }

    public static function deleteSale(int $id)
    {
        $sqlstr = "DELETE FROM sales WHERE saleId = :saleId;";
        return self::executeNonQuery($sqlstr, ["saleId" => $id]);
    }
}
