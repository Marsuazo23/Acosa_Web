<?php
namespace Controllers\Pages;

use \Utilities\Site as Site;
use Views\Renderer;
use Controllers\PublicController;  

class aboutUs extends PublicController
{
    public function run(): void
    {
        // Se agregan los archivos CSS
        Site::addLink("public/css/pages/aboutUs.css");

        // Array que contendrá los datos a enviar a la vista
        $viewData = [];

        // Renderiza la vista y pasa los datos recopilados
        Renderer::render('pages/aboutUs', $viewData);
    }
}
