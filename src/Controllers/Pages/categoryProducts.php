<?php
namespace Controllers\Pages;

use \Utilities\Site as Site;
use Views\Renderer;
use Controllers\PublicController;
use Dao\Products\Categories;

class categoryProducts extends PublicController
{
    public function run(): void
    {
        Site::addLink("public/css/pages/products.css");

        $categoryId = isset($_GET["categoryId"]) ? intval($_GET["categoryId"]) : 0;
        $categoryName = isset($_GET["name"]) ? urldecode($_GET["name"]) : "Categoría";

        // Paginación: 
        $itemsPerPage = isset($_GET["itemsPerPage"]) ? intval($_GET["itemsPerPage"]) : 10;
        $currentPage = isset($_GET["pageNum"]) ? intval($_GET["pageNum"]) : 1;

        // Total productos para esta categoría
        $totalProducts = Categories::countProductsByCategory($categoryId);

        // Cálculo offset para SQL
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Obtener productos para la página actual
        $products = Categories::getProductsByCategory($categoryId, $itemsPerPage, $offset);

        // Formatear datos como antes
        foreach ($products as &$product) {
            if (!empty($product['discount'])) {
                $product['discount'] = '<div class="discount">' . $product['discount'] . '</div>';
                $product['originalPrice'] = '<span class="original-price">L. ' . number_format($product['originalPrice'], 2, '.', ',') . '</span> ';
                $product['productPrice'] = number_format($product['productPrice'], 2, '.', ',');
            } else {
                $product['discount'] = '';
                $product['originalPrice'] = '';
                $product['productPrice'] = number_format($product['productPrice'], 2, '.', ',');
            }
        }
        unset($product);

        // Obtener la paginación
        $pagination = \Utilities\Paging::getPagination(
            $totalProducts, 
            $itemsPerPage, 
            $currentPage, 
            "index.php?page=Pages_categoryProducts&categoryId=$categoryId&name=" . urlencode($categoryName),
            "Pages_categoryProducts"
        );

        $viewData = [
            "categoryName" => $categoryName,
            "products" => $products,
            "pagination" => $pagination
        ];

        Renderer::render("pages/categoryProducts", $viewData);
    }
}


