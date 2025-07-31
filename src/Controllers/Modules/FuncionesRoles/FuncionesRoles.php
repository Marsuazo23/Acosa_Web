<?php

namespace Controllers\Modules\FuncionesRoles;

use Controllers\PrivateController;
use Dao\Modules\FuncionesRoles\FuncionesRoles as FuncionesRolesDAO;
use Views\Renderer;

class FuncionesRoles extends PrivateController
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
        $itemsPerPage = isset($_GET["itemsPerPage"]) ? intval($_GET["itemsPerPage"]) : 10;
        $currentPage = isset($_GET["pageNum"]) ? intval($_GET["pageNum"]) : 1;

        $totalFuncionesRoles = FuncionesRolesDAO::countAll();
        $offset = ($currentPage - 1) * $itemsPerPage;

        $this->viewData["funcionesroles"] = FuncionesRolesDAO::getPaged($itemsPerPage, $offset);

        $this->viewData["pagination"] = \Utilities\Paging::getPagination(
            $totalFuncionesRoles,
            $itemsPerPage,
            $currentPage,
            "index.php?page=Modules-FuncionesRoles-FuncionesRoles&itemsPerPage={$itemsPerPage}",
            "Modules-FuncionesRoles-FuncionesRoles"
        );

        Renderer::render("modules/funcionesroles/funcionesroles", $this->viewData);
    }
}
