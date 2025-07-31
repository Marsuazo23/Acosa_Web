<?php

namespace Controllers\Modules\Sales;

use Controllers\PrivateController;
use Dao\modules\sales\Sales as SalesDAO;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Modules-Sales-Sales";

class Sale extends PrivateController
{
    private array $viewData;
    private array $modes;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [
            "mode" => "",
            "saleId" => 0,
            "productId" => 0,
            "discountPercent" => 0.0,
            "saleStart" => "",
            "saleEnd" => "",
            "modeDsc" => "",
            "errors" => [],
            "cancelLabel" => "Cancelar",
            "showConfirm" => true,
            "readonly" => ""
        ];

        $this->modes = [
            "INS" => "Nueva Oferta",
            "UPD" => "Actualizar Oferta: %s",
            "DEL" => "Eliminar Oferta: %s",
            "DSP" => "Detalles de la Oferta: %s"
        ];
    }

    public function run(): void
    {
        $this->getQueryParamsData();

        if ($this->viewData["mode"] !== "INS") {
            $this->getDataFromDB();
        }

        if ($this->isPostBack()) {
            $this->getBodyData();
            if ($this->validateData()) {
                $this->processData();
            }
        }

        $this->prepareViewData();

        Renderer::render("modules/sales/sale", $this->viewData);
    }

    private function throwError(string $message, string $logMessage = "")
    {
        if (!empty($logMessage)) {
            error_log(sprintf("%s - %s", $this->name, $logMessage));
        }
        Site::redirectToWithMsg(LIST_URL, $message);
    }

    private function innerError(string $scope, string $message)
    {
        if (!isset($this->viewData["errors"][$scope])) {
            $this->viewData["errors"][$scope] = [$message];
        } else {
            $this->viewData["errors"][$scope][] = $message;
        }
    }

    private function getQueryParamsData()
    {
        if (!isset($_GET["mode"])) {
            $this->throwError("Error: Modo no definido.", "Falta parámetro mode");
        }

        $this->viewData["mode"] = $_GET["mode"];

        if (!isset($this->modes[$this->viewData["mode"]])) {
            $this->throwError("Error: Modo inválido.", "Modo inválido: " . $this->viewData["mode"]);
        }

        if ($this->viewData["mode"] !== "INS") {
            if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
                $this->throwError("Error: ID no válido.", "ID faltante o inválido");
            }
            $this->viewData["saleId"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmpSale = SalesDAO::getById($this->viewData["saleId"]);
        if ($tmpSale) {
            $this->viewData["productId"] = $tmpSale["productId"];
            $this->viewData["discountPercent"] = $tmpSale["discountPercent"];
            $this->viewData["saleStart"] = $tmpSale["saleStart"];
            $this->viewData["saleEnd"] = $tmpSale["saleEnd"];
        } else {
            $this->throwError("Error: Registro no encontrado.", "Venta con id " . $this->viewData["saleId"] . " no existe.");
        }
    }

    private function getBodyData()
    {
        if (!isset($_POST["saleId"])) {
            $this->throwError("Error: Falta el campo ID en el formulario.", "Falta el parámetro saleId en POST");
        }
        if (!isset($_POST["productId"])) {
            $this->throwError("Error: Falta el campo producto.", "Falta el parámetro productId en POST");
        }
        if (!isset($_POST["discountPercent"])) {
            $this->throwError("Error: Falta el porcentaje de descuento.", "Falta el parámetro discountPercent en POST");
        }
        if (!isset($_POST["saleStart"])) {
            $this->throwError("Error: Falta la fecha de inicio.", "Falta el parámetro saleStart en POST");
        }
        if (!isset($_POST["saleEnd"])) {
            $this->throwError("Error: Falta la fecha de fin.", "Falta el parámetro saleEnd en POST");
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError("Error: Token de seguridad faltante.", "Falta el parámetro xsrtoken en POST");
        }

        if (intval($_POST["saleId"]) !== $this->viewData["saleId"]) {
            $this->throwError("Error: ID inconsistente.", "ID esperado: " . $this->viewData["saleId"] . " recibido: " . $_POST["saleId"]);
        }

        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError("Error de seguridad: Token inválido.", "Token esperado: " . $_SESSION[$this->name . "-xsrtoken"] . " recibido: " . $_POST["xsrtoken"]);
        }

        $this->viewData["productId"] = intval($_POST["productId"]);
        $this->viewData["discountPercent"] = floatval($_POST["discountPercent"]);
        $this->viewData["saleStart"] = trim($_POST["saleStart"]);
        $this->viewData["saleEnd"] = trim($_POST["saleEnd"]);
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["productId"])) {
            $this->innerError("productId", "Este campo es requerido.");
        } elseif (!is_numeric($this->viewData["productId"]) || $this->viewData["productId"] < 1) {
            $this->innerError("productId", "Debe ser un número válido.");
        }

        if (Validators::IsEmpty($this->viewData["discountPercent"])) {
            $this->innerError("discountPercent", "Este campo es requerido.");
        } elseif (!is_numeric($this->viewData["discountPercent"]) || $this->viewData["discountPercent"] <= 0) {
            $this->innerError("discountPercent", "Debe ser un número mayor que cero.");
        }

        if (Validators::IsEmpty($this->viewData["saleStart"])) {
            $this->innerError("saleStart", "La fecha de inicio de oferta es requerida.");
        }

        if (Validators::IsEmpty($this->viewData["saleEnd"])) {
            $this->innerError("saleEnd", "La fecha de fin de oferta es requerida.");
        }

        return count($this->viewData["errors"]) === 0;
    }

    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "INS":
                if (SalesDAO::insertSale(
                    $this->viewData["productId"],
                    $this->viewData["discountPercent"],
                    $this->viewData["saleStart"],
                    $this->viewData["saleEnd"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Oferta creada correctamente.");
                } else {
                    $this->innerError("global", "Error al insertar la oferta.");
                }
                break;
            case "UPD":
                if (SalesDAO::updateSale(
                    $this->viewData["saleId"],
                    $this->viewData["productId"],
                    $this->viewData["discountPercent"],
                    $this->viewData["saleStart"],
                    $this->viewData["saleEnd"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Oferta actualizada correctamente.");
                } else {
                    $this->innerError("global", "Error al actualizar la oferta.");
                }
                break;
            case "DEL":
                if (SalesDAO::deleteSale($this->viewData["saleId"]) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Oferta eliminada correctamente.");
                } else {
                    $this->innerError("global", "Error al eliminar la oferta.");
                }
                break;
        }
    }

    private function prepareViewData()
    {
        $this->viewData["modeDsc"] = sprintf(
            $this->modes[$this->viewData["mode"]],
            $this->viewData["saleId"]
        );

        if (count($this->viewData["errors"]) > 0) {
            foreach ($this->viewData["errors"] as $scope => $errorsArray) {
                $this->viewData["errors_" . $scope] = $errorsArray;
            }
        }

        if ($this->viewData["mode"] === "DSP") {
            $this->viewData["cancelLabel"] = "Volver";
            $this->viewData["showConfirm"] = false;
        }

        if (in_array($this->viewData["mode"], ["DSP", "DEL"])) {
            $this->viewData["readonly"] = "readonly";
        }

        $this->viewData["timestamp"] = time();
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}
