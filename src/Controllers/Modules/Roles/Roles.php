<?php

namespace Controllers\Modules\Roles;

use Controllers\PrivateCOntroller;
use Dao\modules\roles\Roles as RolesDAO;
use Views\Renderer;

class Roles extends PrivateCOntroller
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
        // Obtener todos los roles desde el DAO
        $this->viewData["roles"] = RolesDAO::getAll();

        // Renderizar la vista del listado de roles
        Renderer::render("modules/roles/roles", $this->viewData);
    }
}
