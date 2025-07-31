<?php

namespace Dao\modules\products;

use Dao\Table;

class Products extends Table
{
    public static function getById(int $id)
    {
        $sqlstr = "SELECT * FROM products WHERE productId = :productId;";
        return self::obtenerUnRegistro($sqlstr, ["productId" => $id]);
    }

    public static function insertProduct(
        string $productName,
        string $productDescription,
        float $productPrice,
        string $productImgUrl,
        string $productStatus,
        int $categoryId,
        int $productStock
    ) {
        $sqlstr = "INSERT INTO products (productName, productDescription, productPrice, productImgUrl, productStatus, categoryId, productStock)
                VALUES (:productName, :productDescription, :productPrice, :productImgUrl, :productStatus, :categoryId, :productStock);";
        return self::executeNonQuery(
            $sqlstr,
            [
                "productName" => $productName,
                "productDescription" => $productDescription,
                "productPrice" => $productPrice,
                "productImgUrl" => $productImgUrl,
                "productStatus" => $productStatus,
                "categoryId" => $categoryId,
                "productStock" => $productStock
            ]
        );
    }

    public static function updateProduct(
        int $id,
        string $productName,
        string $productDescription,
        float $productPrice,
        string $productImgUrl,
        string $productStatus,
        int $categoryId,
        int $productStock
    ) {
        $sqlstr = "UPDATE products SET 
                    productName = :productName,
                    productDescription = :productDescription,
                    productPrice = :productPrice,
                    productImgUrl = :productImgUrl,
                    productStatus = :productStatus,
                    categoryId = :categoryId,
                    productStock = :productStock
                WHERE productId = :productId;";
        return self::executeNonQuery(
            $sqlstr,
            [
                "productName" => $productName,
                "productDescription" => $productDescription,
                "productPrice" => $productPrice,
                "productImgUrl" => $productImgUrl,
                "productStatus" => $productStatus,
                "categoryId" => $categoryId,
                "productStock" => $productStock,
                "productId" => $id
            ]
        );
    }

    public static function deleteProduct(int $id)
    {
        $sqlstr = "DELETE FROM products WHERE productId = :productId;";
        return self::executeNonQuery($sqlstr, ["productId" => $id]);
    }

        public static function countAll(): int
    {
        $sql = "SELECT COUNT(*) as total FROM products";
        $result = self::obtenerUnRegistro($sql, []);
        return $result ? intval($result["total"]) : 0;
    }

    public static function getPaged(int $limit, int $offset): array
    {
        $sql = "SELECT * FROM products LIMIT :limit OFFSET :offset";
        return self::obtenerRegistros($sql, [
            "limit" => $limit,
            "offset" => $offset
        ]);
    }
}
