<?php

namespace Controllers\Modules\Products;

use Controllers\PrivateCOntroller;
use Dao\modules\products\Products as ProductsDAO;
use Views\Renderer;

class Products extends PrivateCOntroller
{
    private array $viewData;

    public function __construct()
    {
        parent::__construct();  // Llamamos al padre para autenticar/autorización
        $this->viewData = [];

        // Definimos permisos para distintas acciones según feature
        $this->viewData["isNewEnabled"] = parent::isFeatureAutorized($this->name . "\\new");
        $this->viewData["isUpdateEnabled"] = parent::isFeatureAutorized($this->name . "\\update");
        $this->viewData["isDeleteEnabled"] = parent::isFeatureAutorized($this->name . "\\delete");
    }

    public function run(): void
    {
        // Parámetros de paginación
        $itemsPerPage = isset($_GET["itemsPerPage"]) ? intval($_GET["itemsPerPage"]) : 10;
        $currentPage = isset($_GET["pageNum"]) ? intval($_GET["pageNum"]) : 1;

        // Total de productos
        $totalProducts = ProductsDAO::countAll();

        // Calcular offset
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Obtener productos paginados
        $this->viewData["products"] = ProductsDAO::getPaged($itemsPerPage, $offset);

        // Paginación 
        $this->viewData["pagination"] = \Utilities\Paging::getPagination(
            $totalProducts,
            $itemsPerPage,
            $currentPage,
            "index.php?page=Modules-Products-Products&itemsPerPage={$itemsPerPage}",
            "Modules-Products-Products"
        );

        // Renderizar vista
        Renderer::render("modules/products/products", $this->viewData);
    }

}
