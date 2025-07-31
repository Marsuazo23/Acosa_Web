<?php

namespace Controllers\Modules\Funciones;

use Controllers\PrivateController;
use Dao\modules\funciones\Funciones as FuncionesDAO;
use Views\Renderer;

use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Modules-Funciones-Funciones";

class Funcion extends PrivateController
{
    private array $viewData;
    private array $modes;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [
            "mode" => "",
            "fnid" => 0,
            "fncod" => "",
            "fndsc" => "",
            "fnest" => "",
            "fntyp" => "",
            "modeDsc" => "",
            "errors" => [],
            "cancelLabel" => "Cancelar",
            "showConfirm" => true,
            "readonly" => ""
        ];

        $this->modes = [
            "INS" => "Nueva Función",
            "UPD" => "Actualizar Función: %s",
            "DEL" => "Eliminar Función: %s",
            "DSP" => "Detalles de la Función: %s"
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

        Renderer::render("modules/funciones/funcion", $this->viewData);
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
            $this->viewData["fnid"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmpFuncion = FuncionesDAO::getById($this->viewData["fnid"]);
        if ($tmpFuncion) {
            $this->viewData["fncod"] = $tmpFuncion["fncod"];
            $this->viewData["fndsc"] = $tmpFuncion["fndsc"];
            $this->viewData["fnest"] = $tmpFuncion["fnest"];
            $this->viewData["fntyp"] = $tmpFuncion["fntyp"];
        } else {
            $this->throwError("Error: Registro no encontrado.", "Función con id " . $this->viewData["fnid"] . " no existe.");
        }
    }

    private function getBodyData()
    {
        if (!isset($_POST["fnid"])) {
            $this->throwError("Error: Falta el campo ID en el formulario.", "Falta el parámetro fnid en POST");
        }
        if (!isset($_POST["fncod"])) {
            $this->throwError("Error: Falta el código de función.", "Falta el parámetro fncod en POST");
        }
        if (!isset($_POST["fndsc"])) {
            $this->throwError("Error: Falta la descripción.", "Falta el parámetro fndsc en POST");
        }
        if (!isset($_POST["fnest"])) {
            $this->throwError("Error: Falta el estado.", "Falta el parámetro fnest en POST");
        }
        if (!isset($_POST["fntyp"])) {
            $this->throwError("Error: Falta el tipo.", "Falta el parámetro fntyp en POST");
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError("Error: Token de seguridad faltante.", "Falta el parámetro xsrtoken en POST");
        }

        if (intval($_POST["fnid"]) !== $this->viewData["fnid"]) {
            $this->throwError("Error: ID inconsistente.", "ID esperado: " . $this->viewData["fnid"] . " recibido: " . $_POST["fnid"]);
        }

        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError("Error de seguridad: Token inválido.", "Token esperado: " . $_SESSION[$this->name . "-xsrtoken"] . " recibido: " . $_POST["xsrtoken"]);
        }

        $this->viewData["fncod"] = trim($_POST["fncod"]);
        $this->viewData["fndsc"] = trim($_POST["fndsc"]);
        $this->viewData["fnest"] = trim($_POST["fnest"]);
        $this->viewData["fntyp"] = trim($_POST["fntyp"]);
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["fncod"])) {
            $this->innerError("fncod", "Este campo es requerido.");
        }
        if (strlen($this->viewData["fncod"]) > 255) {
            $this->innerError("fncod", "Máximo 255 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["fndsc"])) {
            $this->innerError("fndsc", "Este campo es requerido.");
        }
        if (strlen($this->viewData["fndsc"]) > 255) {
            $this->innerError("fndsc", "Máximo 255 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["fnest"])) {
            $this->innerError("fnest", "Este campo es requerido.");
        }
        if (strlen($this->viewData["fnest"]) > 3) {
            $this->innerError("fnest", "Máximo 3 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["fntyp"])) {
            $this->innerError("fntyp", "Este campo es requerido.");
        }
        if (strlen($this->viewData["fntyp"]) > 3) {
            $this->innerError("fntyp", "Máximo 3 caracteres.");
        }

        return count($this->viewData["errors"]) === 0;
    }


    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "INS":
                if (FuncionesDAO::insertFuncion(
                    $this->viewData["fncod"],
                    $this->viewData["fndsc"],
                    $this->viewData["fnest"],
                    $this->viewData["fntyp"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Función creada correctamente.");
                } else {
                    $this->innerError("global", "Error al insertar la función.");
                }
                break;
            case "UPD":
                if (FuncionesDAO::updateFuncion(
                    $this->viewData["fnid"],
                    $this->viewData["fncod"],
                    $this->viewData["fndsc"],
                    $this->viewData["fnest"],
                    $this->viewData["fntyp"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Función actualizada correctamente.");
                } else {
                    $this->innerError("global", "Error al actualizar la función.");
                }
                break;
            case "DEL":
                if (FuncionesDAO::deleteFuncion($this->viewData["fnid"]) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Función eliminada correctamente.");
                } else {
                    $this->innerError("global", "Error al eliminar la función.");
                }
                break;
        }
    }

    private function prepareViewData()
    {
        $this->viewData["modeDsc"] = sprintf(
            $this->modes[$this->viewData["mode"]],
            $this->viewData["fncod"]
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
            $this->viewData["disabled"] = "disabled";
        }

        $this->viewData["fnest_ACT"] = ($this->viewData["fnest"] === "ACT") ? 'selected="selected"' : '';
        $this->viewData["fnest_INA"] = ($this->viewData["fnest"] === "INA") ? 'selected="selected"' : '';
        $this->viewData["fnest_PEN"] = ($this->viewData["fnest"] === "PEN") ? 'selected="selected"' : '';

        $this->viewData["fntyp_MNU"] = ($this->viewData["fntyp"] === "MNU") ? 'selected="selected"' : '';
        $this->viewData["fntyp_CTR"] = ($this->viewData["fntyp"] === "CTR") ? 'selected="selected"' : '';
        $this->viewData["fntyp_FNC"] = ($this->viewData["fntyp"] === "FNC") ? 'selected="selected"' : '';

        $this->viewData["timestamp"] = time();
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}
