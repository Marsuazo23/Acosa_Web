<?php

namespace Controllers\Modules\Orders;

use Controllers\PrivateController;
use Dao\Orders\Orders as OrdenesDAO;
use Views\Renderer;

class Orders extends PrivateController
{
    private array $viewData = [];

    public function __construct()
    {
        parent::__construct();

        // Permisos para el módulo
        $this->viewData["isUpdateEnabled"] = parent::isFeatureAutorized($this->name . "\\update");
    }

    public function run(): void
    {
        // Listado de todas las órdenes
        $this->viewData["orders"] = OrdenesDAO::getAll();

        Renderer::render("modules/orders/orders", $this->viewData);
    }
}
