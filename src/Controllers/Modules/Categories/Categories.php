<?php

namespace Controllers\Modules\Categories;

use Controllers\PrivateController;
use Dao\modules\categories\Category as CategoriesDAO;
use Views\Renderer;

class Categories extends PrivateController
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
        // Obtener todas las categorías
        $this->viewData["categories"] = CategoriesDAO::getAll();

        // Renderizar la vista correspondiente
        Renderer::render("modules/categories/categories", $this->viewData);
    }
}
