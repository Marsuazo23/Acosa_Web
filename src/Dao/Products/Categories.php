<?php
namespace Dao\Products;
use Dao\Table;

class Categories extends Table {
    public static function getOneProductPerCategory() {
      $sqlstr = "SELECT p.productId, p.productName, p.productDescription, p.productPrice, p.productImgUrl, p.productStatus, c.categoryName 
                FROM products p 
                INNER JOIN categories c ON p.categoryId = c.categoryId 
                WHERE p.productStatus = 'ACT' 
                  AND p.productId NOT IN (SELECT productId FROM sales WHERE saleStart <= NOW() AND saleEnd >= NOW()) 
                  AND p.productId = (SELECT MIN(p2.productId) 
                                      FROM products p2 
                                      WHERE p2.categoryId = p.categoryId 
                                        AND p2.productStatus = 'ACT' 
                                        AND p2.productId NOT IN (SELECT productId FROM sales WHERE saleStart <= NOW() AND saleEnd >= NOW())) 
                ORDER BY c.categoryName ASC, p.productName ASC
                LIMIT 4";
      $params = [];
      $registros = self::obtenerRegistros($sqlstr, $params);
      return $registros;
    }
    
    public static function getProductsByCategory($categoryId, $limit = 0, $offset = 0){
      $limitOffsetStr = "";
      $params = [];
      if ($limit > 0) {
          $limitOffsetStr = " LIMIT :limit OFFSET :offset";
          $params = ["limit" => $limit, "offset" => $offset];
      }
      
      if ($categoryId == 5) {
          $sqlstr = "SELECT 
                        p.productId, 
                        p.productName, 
                        p.productDescription, 
                        p.productImgUrl, 
                        p.productPrice AS originalPrice, 
                        ROUND(p.productPrice - (p.productPrice * s.discountPercent / 100), 2) AS productPrice, 
                        CONCAT('-', CAST(s.discountPercent AS UNSIGNED), '%') AS discount 
                    FROM products p 
                    INNER JOIN sales s ON p.productId = s.productId 
                    AND s.saleStart <= NOW() AND s.saleEnd >= NOW() 
                    WHERE p.productStatus = 'ACT' 
                    ORDER BY p.productName ASC" . $limitOffsetStr;
      } else {
          $sqlstr = "SELECT 
                        p.productId, 
                        p.productName, 
                        p.productDescription, 
                        p.productImgUrl, 
                        p.productPrice AS originalPrice, 
                        IF(s.discountPercent IS NOT NULL AND s.saleStart <= NOW() AND s.saleEnd >= NOW(), 
                          ROUND(p.productPrice - (p.productPrice * s.discountPercent / 100), 2), 
                          p.productPrice) AS productPrice, 
                        IF(s.discountPercent IS NOT NULL AND s.saleStart <= NOW() AND s.saleEnd >= NOW(), 
                          CONCAT('-', CAST(s.discountPercent AS UNSIGNED), '%'),  
                          NULL) AS discount 
                    FROM products p 
                    LEFT JOIN sales s ON p.productId = s.productId 
                    AND s.saleStart <= NOW() AND s.saleEnd >= NOW() 
                    WHERE p.categoryId = :categoryId AND p.productStatus = 'ACT' 
                    ORDER BY p.productName ASC" . $limitOffsetStr;
          $params["categoryId"] = $categoryId;
      }
      return self::obtenerRegistros($sqlstr, $params);
    }

    public static function countProductsByCategory($categoryId) {
      if ($categoryId == 5) {
          $sqlstr = "SELECT COUNT(*) as total FROM products p 
                    INNER JOIN sales s ON p.productId = s.productId 
                    AND s.saleStart <= NOW() AND s.saleEnd >= NOW() 
                    WHERE p.productStatus = 'ACT'";
          $params = [];
      } else {
          $sqlstr = "SELECT COUNT(*) as total FROM products p 
                    WHERE p.categoryId = :categoryId AND p.productStatus = 'ACT'";
          $params = ["categoryId" => $categoryId];
      }
      $row = self::obtenerUnRegistro($sqlstr, $params);
      return $row ? intval($row["total"]) : 0;
    }
  }
?>