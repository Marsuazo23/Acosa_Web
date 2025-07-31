<?php

namespace Controllers\Modules\Transactions;

use Controllers\PrivateController;
use Dao\Paypal\PaypalTransactions;
use Views\Renderer;

class transactions extends PrivateController
{
    private array $viewData;

    public function __construct()
    {
        parent::__construct(); // Verifica autenticación y autorización
        $this->viewData = [];
    }

    public function run(): void
    {
        // Obtener todas las transacciones
        $this->viewData["transactions"] = PaypalTransactions::getAllTransactions();

        // Renderizar la vista que mostrará la tabla de transacciones
        Renderer::render("modules/transactions/transactions", $this->viewData);
    }
}
