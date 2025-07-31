<?php
namespace Controllers\Pages;

use \Utilities\Site as Site;
use Views\Renderer;
use Controllers\PublicController;

class informacion extends PublicController
{
    public function run(): void
    {
        Site::addLink("public/css/pages/informacion.css");
        $viewData = [];
        Renderer::render('pages/informacion', $viewData);
    }
}
