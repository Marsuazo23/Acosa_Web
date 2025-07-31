<?php
namespace Controllers\Pages;

use \Utilities\Site as Site;
use Views\Renderer;
use Controllers\PublicController;  

class categories extends PublicController
{
    public function run(): void
    {
        // Se agregan los archivos CSS
        Site::addLink("public/css/pages/categories.css");
        
        // Array que contendrá los datos a enviar a la vista
        $viewData = [];
        
        // Renderiza la vista y pasa los datos recopilados
        Renderer::render('pages/categories', $viewData);
    }
}
