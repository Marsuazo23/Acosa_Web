<?php

namespace Controllers\Modules\Transactions;

use Controllers\PrivateController;
use Dao\Paypal\PaypalTransactions;
use Views\Renderer;

class Transaction extends PrivateController
{
    private array $viewData;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [];
    }

    public function run(): void
    {
        $id = $_GET["id"] ?? null;

        if (!$id) {
            \Utilities\Site::redirectTo("index.php?page=Modules-Transactions-Transactions");
            return;
        }

        $transaction = PaypalTransactions::getById((int)$id);

        if (!$transaction) {
            \Utilities\Site::redirectTo("index.php?page=Modules-Transactions-Transactions");
            return;
        }

        foreach ($transaction as $key => $value) {
            $this->viewData[$key] = $value;
        }

        $this->viewData["modeDsc"] = "Detalle de Transacción PayPal";
        $this->viewData["mode"] = "DSP";
        $this->viewData["cancelLabel"] = "Regresar";
        $this->viewData["readonly"] = "readonly";

        Renderer::render("modules/transactions/transaction", $this->viewData);
    }
}
