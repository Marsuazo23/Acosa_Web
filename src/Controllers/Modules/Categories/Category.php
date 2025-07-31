<?php

namespace Controllers\Modules\Categories;

use Controllers\PrivateController;
use Dao\modules\categories\Category as CategoriesDAO;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Modules-Categories-Categories";

class Category extends PrivateController
{
    private array $viewData;
    private array $modes;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [
            "mode" => "",
            "categoryId" => 0,
            "categoryName" => "",
            "modeDsc" => "",
            "errors" => [],
            "cancelLabel" => "Cancelar",
            "showConfirm" => true,
            "readonly" => ""
        ];

        $this->modes = [
            "INS" => "Nueva Categoría",
            "UPD" => "Actualizar Categoría: %s",
            "DEL" => "Eliminar Categoría: %s",
            "DSP" => "Detalles de la Categoría: %s"
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

        Renderer::render("modules/categories/category", $this->viewData);
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
            $this->viewData["categoryId"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmpCategory = CategoriesDAO::getById($this->viewData["categoryId"]);
        if ($tmpCategory) {
            $this->viewData["categoryName"] = $tmpCategory["categoryName"];
        } else {
            $this->throwError("Error: Registro no encontrado.", "Categoría con id " . $this->viewData["categoryId"] . " no existe.");
        }
    }

    private function getBodyData()
    {
        if (!isset($_POST["categoryId"])) {
            $this->throwError("Error: Falta el campo ID en el formulario.", "Falta el parámetro categoryId en POST");
        }
        if (!isset($_POST["categoryName"])) {
            $this->throwError("Error: Falta el nombre de la categoría.", "Falta el parámetro categoryName en POST");
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError("Error: Token de seguridad faltante.", "Falta el parámetro xsrtoken en POST");
        }

        if (intval($_POST["categoryId"]) !== $this->viewData["categoryId"]) {
            $this->throwError("Error: ID inconsistente.", "ID esperado: " . $this->viewData["categoryId"] . " recibido: " . $_POST["categoryId"]);
        }

        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError("Error de seguridad: Token inválido.", "Token esperado: " . $_SESSION[$this->name . "-xsrtoken"] . " recibido: " . $_POST["xsrtoken"]);
        }

        $this->viewData["categoryName"] = trim($_POST["categoryName"]);
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["categoryName"])) {
            $this->innerError("categoryName", "Este campo es requerido.");
        }
        if (strlen($this->viewData["categoryName"]) > 100) {
            $this->innerError("categoryName", "Máximo 100 caracteres.");
        }

        return count($this->viewData["errors"]) === 0;
    }

    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "INS":
                if (CategoriesDAO::insertCategory(
                    $this->viewData["categoryName"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Categoría creada correctamente.");
                } else {
                    $this->innerError("global", "Error al insertar la categoría.");
                }
                break;
            case "UPD":
                if (CategoriesDAO::updateCategory(
                    $this->viewData["categoryId"],
                    $this->viewData["categoryName"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Categoría actualizada correctamente.");
                } else {
                    $this->innerError("global", "Error al actualizar la categoría.");
                }
                break;
            case "DEL":
                if (CategoriesDAO::deleteCategory($this->viewData["categoryId"]) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Categoría eliminada correctamente.");
                } else {
                    $this->innerError("global", "Error al eliminar la categoría.");
                }
                break;
        }
    }

    private function prepareViewData()
    {
        $this->viewData["modeDsc"] = sprintf(
            $this->modes[$this->viewData["mode"]],
            $this->viewData["categoryName"]
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
