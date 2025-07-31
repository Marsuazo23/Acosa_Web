<?php
namespace Controllers\Pages;

use \Utilities\Site as Site;
use Views\Renderer;
use Controllers\PublicController;
use Dao\Products\Products as ProductsDao;

class detailProducts extends PublicController
{
    public function run(): void
    {
        // Se agregan los archivos CSS
        Site::addLink("public/css/pages/detailProducts.css");
        Site::addLink("public/css/pages/products.css");

        // Obtiene el ID del producto desde la URL (GET), si no existe se asigna 0
        $productId = isset($_GET['productId']) ? intval($_GET['productId']) : 0;

        // Inicializa un arreglo vacío para pasar datos a la vista
        $viewData = [];

        // Verifica que el ID del producto sea mayor que 0
        if ($productId > 0) {
            // Obtiene los datos del producto desde el DAO (modelo)
            $product = ProductsDao::getProductById($productId);

            // Si el producto existe (no es nulo)
            if ($product) {
                // Formatea el precio original con dos decimales y coma como separador de miles
                $product['originalPrice'] = number_format($product['originalPrice'], 2, '.', ',');

                // Formatea el precio con descuento de igual forma
                $product['productPrice'] = number_format($product['productPrice'], 2, '.', ',');

                // Convierte el stock a entero para asegurarse del tipo
                $product['productStock'] = intval($product['productStock']);

                // Si el producto tiene un descuento activo, prepara el HTML para mostrar precio tachado y precio con descuento
                if (!empty($product['discount'])) {
                    $product['displayPrice'] = '<span class="original-price">L. ' . $product['originalPrice'] . '</span> L. ' . $product['productPrice'];
                } else {
                    // Si no hay descuento, muestra sólo el precio original
                    $product['displayPrice'] = 'L. ' . $product['originalPrice'];
                }

                // Array que contendrá los datos a enviar a la vista
                $viewData = $product;
            }
        } else {
            // Si no hay un productId válido, redirige a la página de categorías
            Site::redirectTo("index.php?page=Pages\categories");
            return;
        }

        // Renderiza la vista y pasa los datos recopilados
        Renderer::render("pages/detailProducts", $viewData);
    }
}

