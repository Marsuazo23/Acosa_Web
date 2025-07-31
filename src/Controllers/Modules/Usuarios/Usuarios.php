<?php

namespace Controllers\Modules\Usuarios;

use Controllers\PrivateController;
use Dao\modules\usuarios\Usuarios as UsuarioDAO;
use Views\Renderer;

class Usuarios extends PrivateController
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
        // Obtener todos los usuarios (sin mostrar password ni useractcod)
        $this->viewData["usuarios"] = UsuarioDAO::getAll();

        // Renderizar la vista correspondiente
        Renderer::render("modules/usuarios/usuarios", $this->viewData);
    }
}
