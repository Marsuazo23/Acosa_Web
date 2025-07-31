<?php

namespace Controllers\Modules\Sales;

use Controllers\PrivateController;
use Dao\modules\sales\Sales as SalesDAO;
use Views\Renderer;

class Sales extends PrivateController
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
        // Obtener todas las ventas
        $this->viewData["sales"] = SalesDAO::getAll();

        // Renderizar la vista correspondiente
        Renderer::render("modules/sales/sales", $this->viewData);
    }
}
