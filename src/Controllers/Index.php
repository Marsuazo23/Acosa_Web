<?php
/**
 * PHP Version 7.2
 *
 * @category Public
 * @package  Controllers
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  MIT http://
 * @version  CVS:1.0.0
 * @link     http://
 */
namespace Controllers;

/**
 * Index Controller
 *
 * @category Public
 * @package  Controllers
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  MIT http://
 * @link     http://
 */

use \Dao\Products\Products as ProductsDao;
use \Dao\Products\Categories as CategoriesDao;
use \Views\Renderer as Renderer;
use \Utilities\Site as Site;

class Index extends PublicController
{
    public function run(): void
    {
        // Se agregan los archivos CSS 
        Site::addLink("public/css/Pages/products.css");
        Site::addLink("public/css/Pages/slider.css");
        Site::addLink("public/css/Pages/ads.css");

        // Array que contendrá los datos a enviar a la vista
        $viewData = [];

        // Obtiene los productos en oferta diaria desde la base de datos
        $viewData["productsOnSale"] = ProductsDao::getDailyDeals();

        // Obtiene un producto destacado por cada categoría
        $viewData["featuredByCategory"] = CategoriesDao::getOneProductPerCategory();

        // Renderiza la vista y pasa los datos recopilados
        Renderer::render("pages/index", $viewData);
    }
}
?>
