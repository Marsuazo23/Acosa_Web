<?php

namespace Dao\Cart;

class Cart extends \Dao\Table
{
     /**
     * Agrega un producto al carrito de un usuario autenticado.
     * Si el producto ya existe en el carrito, incrementa la cantidad.
     * Calcula el precio tomando en cuenta descuentos activos.
     */
    public static function addToCarretilla($usercod, $productId, $quantity)
    {
        $sql = "INSERT INTO carretilla (usercod, productId, crrctd, crrprc, crrfching)
                VALUES (:usercod, :productId, :crrctd, 
                    (SELECT 
                        ROUND(p.productPrice - IFNULL(p.productPrice * s.discountPercent / 100, 0), 2)
                    FROM products p
                    LEFT JOIN sales s ON p.productId = s.productId
                        AND s.saleStart <= NOW() AND s.saleEnd >= NOW()
                    WHERE p.productId = :productId
                    ), 
                    NOW())
                ON DUPLICATE KEY UPDATE 
                    crrctd = crrctd + VALUES(crrctd),
                    crrfching = NOW();";
        return self::executeNonQuery($sql, [
            "usercod" => $usercod,
            "productId" => $productId,
            "crrctd" => $quantity
        ]);
    }

    /* Agrega un producto al carrito anónimo (usuario no autenticado). */
    public static function addToCarretillaAnon($anoncod, $productId, $quantity)
    {
        $sql = "INSERT INTO carretillaanon (anoncod, productId, crrctd, crrprc, crrfching)
                VALUES (:anoncod, :productId, :crrctd,
                    (SELECT 
                        ROUND(p.productPrice - IFNULL(p.productPrice * s.discountPercent / 100, 0), 2)
                    FROM products p
                    LEFT JOIN sales s ON p.productId = s.productId
                        AND s.saleStart <= NOW() AND s.saleEnd >= NOW()
                    WHERE p.productId = :productId
                    ),
                    NOW())
                ON DUPLICATE KEY UPDATE 
                    crrctd = crrctd + VALUES(crrctd),
                    crrfching = NOW();";
        return self::executeNonQuery($sql, [
            "anoncod" => $anoncod,
            "productId" => $productId,
            "crrctd" => $quantity
        ]);
    }

    /**
     * Obtiene el número total de artículos en el carrito del usuario
     * (logueado o anónimo según se pase el parámetro).
     */
    public static function getCartCount($usercod = null)
    {
        if ($usercod) {
            $sql = "SELECT SUM(crrctd) as total FROM carretilla WHERE usercod = :usercod";
            $result = self::obtenerUnRegistro($sql, ["usercod" => $usercod]);
        } else {
            // Obtener el anoncod igual que al agregar (cookie o función)
            $anoncod = $_COOKIE["anoncod"] ?? \Utilities\Cart\CartFns::getAnnonCartCode();
            $result = self::obtenerUnRegistro(
                "SELECT SUM(crrctd) as total FROM carretillaanon WHERE anoncod = :anoncod",
                ["anoncod" => $anoncod]
            );
        }
        return $result ? intval($result["total"]) : 0;
    }

    /* Devuelve todos los productos del carrito de un usuario autenticado. */
    public static function getCartProducts($usercod)
    {
        $sql = "SELECT c.productId, p.productName, p.productDescription, p.productImgUrl,
                    c.crrctd, c.crrprc
                FROM carretilla c
                INNER JOIN products p ON c.productId = p.productId
                WHERE c.usercod = :usercod;";
        return self::obtenerRegistros($sql, ["usercod" => $usercod]);
    }

    /* Devuelve todos los productos del carrito de un usuario anónimo. */
    public static function getCartProductsAnon($anoncod)
    {
        $sql = "SELECT c.productId, p.productName, p.productDescription, p.productImgUrl,
                    c.crrctd, c.crrprc
                FROM carretillaanon c
                INNER JOIN products p ON c.productId = p.productId
                WHERE c.anoncod = :anoncod;";
        return self::obtenerRegistros($sql, ["anoncod" => $anoncod]);
    }

    /* Elimina un producto específico del carrito de un usuario autenticado. */
    public static function removeFromCarretilla($usercod, $productId)
    {
        $sql = "DELETE FROM carretilla WHERE usercod = :usercod AND productId = :productId;";
        return self::executeNonQuery($sql, [
            "usercod" => $usercod,
            "productId" => $productId
        ]);
    }

    /* Elimina un producto específico del carrito de un usuario anónimo. */
    public static function removeFromCarretillaAnon($anoncod, $productId)
    {
        $sql = "DELETE FROM carretillaanon WHERE anoncod = :anoncod AND productId = :productId;";
        return self::executeNonQuery($sql, [
            "anoncod" => $anoncod,
            "productId" => $productId
        ]);
    }

    /* Elimina carritos expirados (autenticados y anónimos) según el tiempo configurado. */
    public static function clearExpiredCarts()
    {
        $deltaAuth = \Utilities\Cart\CartFns::getAuthTimeDelta();
        $deltaAnon = \Utilities\Cart\CartFns::getUnAuthTimeDelta();

        // Contar filas expiradas usuarios autenticados
        $sqlCountAuth = "SELECT COUNT(*) AS cnt FROM carretilla WHERE TIME_TO_SEC(TIMEDIFF(NOW(), crrfching)) > :delta";
        $countAuth = self::obtenerUnRegistro($sqlCountAuth, ["delta" => $deltaAuth])["cnt"];

        // Contar filas expiradas usuarios anónimos
        $sqlCountAnon = "SELECT COUNT(*) AS cnt FROM carretillaanon WHERE TIME_TO_SEC(TIMEDIFF(NOW(), crrfching)) > :delta";
        $countAnon = self::obtenerUnRegistro($sqlCountAnon, ["delta" => $deltaAnon])["cnt"];

        // Ejecutar borrado solo si hay filas expiradas
        if ($countAuth > 0) {
            self::executeNonQuery(
                "DELETE FROM carretilla WHERE TIME_TO_SEC(TIMEDIFF(NOW(), crrfching)) > :delta",
                ["delta" => $deltaAuth]
            );
        }

        if ($countAnon > 0) {
            self::executeNonQuery(
                "DELETE FROM carretillaanon WHERE TIME_TO_SEC(TIMEDIFF(NOW(), crrfching)) > :delta",
                ["delta" => $deltaAnon]
            );
        }

        return ($countAuth + $countAnon) > 0;
    }

    /**
     * Mueve los productos de un carrito anónimo al carrito del usuario autenticado
     * al iniciar sesión y luego limpia el carrito anónimo.
     */
    public static function moveAnonToAuth($anoncod, $usercod)
    {
        // Mueve los productos del carrito anónimo al carrito del usuario autenticado
        $sql = "INSERT INTO carretilla (usercod, productId, crrctd, crrprc, crrfching)
                SELECT :usercod, productId, crrctd, crrprc, NOW()
                FROM carretillaanon
                WHERE anoncod = :anoncod
                ON DUPLICATE KEY UPDATE
                    crrctd = carretilla.crrctd + VALUES(crrctd),
                    crrfching = NOW()";
        self::executeNonQuery($sql, [
            "usercod" => $usercod,
            "anoncod" => $anoncod
        ]);

        // Luego limpia el carrito anónimo
        self::executeNonQuery("DELETE FROM carretillaanon WHERE anoncod = :anoncod", [
            "anoncod" => $anoncod
        ]);
    }

    /* Actualiza la cantidad de un producto en el carrito de un usuario autenticado. */
    public static function updateQuantity($usercod, $productId, $quantity)
    {
        $sql = "UPDATE carretilla SET crrctd = :crrctd, crrfching = NOW()
                WHERE usercod = :usercod AND productId = :productId";
        return self::executeNonQuery($sql, [
            "crrctd" => $quantity,
            "usercod" => $usercod,
            "productId" => $productId
        ]);
    }

    /* Actualiza la cantidad de un producto en el carrito de un usuario anónimo. */
    public static function updateQuantityAnon($anoncod, $productId, $quantity)
    {
        $sql = "UPDATE carretillaanon SET crrctd = :crrctd, crrfching = NOW()
                WHERE anoncod = :anoncod AND productId = :productId";
        return self::executeNonQuery($sql, [
            "crrctd" => $quantity,
            "anoncod" => $anoncod,
            "productId" => $productId
        ]);
    }

    /* Limpia (elimina) todos los productos del carrito de un usuario autenticado. */
    public static function clearCartByUser(int $usercod)
    {
        $sql = "DELETE FROM carretilla WHERE usercod = :usercod";
        return self::executeNonQuery($sql, ["usercod" => $usercod]);
    }

    /* Limpia (elimina) todos los productos del carrito de un usuario anónimo. */
    public static function clearCartByAnon(string $anoncod)
    {
        $sql = "DELETE FROM carretillaanon WHERE anoncod = :anoncod";
        return self::executeNonQuery($sql, ["anoncod" => $anoncod]);
    }

    /* Se reducen los stocks de los productos. */
    public static function reduceStock($productId, $quantity)
    {
        $sql = "UPDATE products 
                SET productStock = productStock - :quantity
                WHERE productId = :productId AND productStock >= :quantity";
        return self::executeNonQuery($sql, [
            "productId" => $productId,
            "quantity" => $quantity
        ]);
    }
}
