<?php
namespace Dao\Products;
use Dao\Table;

class Products extends Table {
    public static function getDailyDeals() {
      $sqlstr = "SELECT p.productId, p.productName, p.productDescription, p.productImgUrl, 
                        p.productPrice AS originalPrice, 
                        ROUND(p.productPrice - (p.productPrice * s.discountPercent / 100), 2) AS productPrice, 
                        CONCAT('-', CAST(s.discountPercent AS UNSIGNED), '%') AS discount 
                FROM products p 
                INNER JOIN sales s ON p.productId = s.productId 
                WHERE s.saleStart <= NOW() AND s.saleEnd >= NOW() AND p.productStatus = 'ACT' 
                LIMIT 4";
      $params = [];
      $registros = self::obtenerRegistros($sqlstr, $params);
      return $registros;
    }
      
    public static function getProductById(int $productId) {
      $sqlstr = "SELECT p.productId, p.productName, p.productDescription, 
                        p.productPrice AS originalPrice, p.productImgUrl, 
                        p.productStatus, p.productStock, 
                        ROUND(p.productPrice - (p.productPrice * s.discountPercent / 100), 2) AS productPrice, 
                        CONCAT('-', CAST(s.discountPercent AS UNSIGNED), '%') AS discount 
                FROM products p 
                LEFT JOIN sales s ON p.productId = s.productId 
                AND s.saleStart <= NOW() AND s.saleEnd >= NOW() 
                WHERE p.productId = :productId AND p.productStatus = 'ACT' 
                LIMIT 1";
      $params = ["productId" => $productId];
      $registro = self::obtenerUnRegistro($sqlstr, $params);
      return $registro;
    }
  }
?>