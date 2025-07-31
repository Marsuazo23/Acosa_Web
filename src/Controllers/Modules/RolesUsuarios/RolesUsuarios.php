<?php

namespace Controllers\Modules\RolesUsuarios;

use Controllers\PrivateController;
use Dao\modules\rolesusuarios\RolesUsuarios as RolesUsuariosDAO;
use Views\Renderer;

class RolesUsuarios extends PrivateController
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
        // Obtener todos los roles de usuario
        $this->viewData["rolesusuarios"] = RolesUsuariosDAO::getAll();

        // Renderizar la vista correspondiente
        Renderer::render("modules/rolesusuarios/rolesusuarios", $this->viewData);
    }
}
