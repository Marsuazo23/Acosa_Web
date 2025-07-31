<?php

namespace Controllers\Modules\Funciones;

use Controllers\PrivateController;
use Dao\modules\funciones\Funciones as FuncionesDAO;
use Views\Renderer;

class Funciones extends PrivateController
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

        // Total de funciones
        $totalFunciones = FuncionesDAO::countAll();

        // Calcular offset
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Obtener funciones paginadas
        $this->viewData["funciones"] = FuncionesDAO::getPaged($itemsPerPage, $offset);

        // Generar paginación
        $this->viewData["pagination"] = \Utilities\Paging::getPagination(
            $totalFunciones,
            $itemsPerPage,
            $currentPage,
            "index.php?page=Modules-Funciones-Funciones&itemsPerPage={$itemsPerPage}",
            "Modules-Funciones-Funciones"
        );

        // Renderizar vista
        Renderer::render("modules/funciones/funciones", $this->viewData);
    }
}
