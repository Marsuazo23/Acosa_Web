<?php

namespace Controllers\Modules\FuncionesRoles;

use Controllers\PrivateController;
use Dao\Modules\FuncionesRoles\FuncionesRoles as FuncionesRolesDAO;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Modules-FuncionesRoles-FuncionesRoles";

class FuncionesRol extends PrivateController
{
    private array $viewData;
    private array $modes;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [
            "mode" => "",
            "fnrolid" => 0,
            "rolescod" => "",
            "fncod" => "",
            "fnrolest" => "",
            "fnexp" => "",
            "modeDsc" => "",
            "errors" => [],
            "cancelLabel" => "Cancelar",
            "showConfirm" => true,
            "readonly" => ""
        ];

        $this->modes = [
            "INS" => "Nueva Relación Función-Rol",
            "UPD" => "Actualizar Función-Rol: %s",
            "DEL" => "Eliminar Función-Rol: %s",
            "DSP" => "Detalles Función-Rol: %s"
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

        Renderer::render("modules/funcionesroles/funcionesrol", $this->viewData);
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
            $this->viewData["fnrolid"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmpData = FuncionesRolesDAO::getById($this->viewData["fnrolid"]);
        if ($tmpData) {
            $this->viewData["rolescod"] = $tmpData["rolescod"];
            $this->viewData["fncod"] = $tmpData["fncod"];
            $this->viewData["fnrolest"] = $tmpData["fnrolest"];
            $this->viewData["fnexp"] = $tmpData["fnexp"];
        } else {
            $this->throwError("Error: Registro no encontrado.", "Función-Rol con id " . $this->viewData["fnrolid"] . " no existe.");
        }
    }

    private function getBodyData()
    {
        if (!isset($_POST["rolescod"])) {
            $this->throwError("Error: Falta el código de rol.", "Falta el parámetro rolescod en POST");
        }
        if (!isset($_POST["fncod"])) {
            $this->throwError("Error: Falta el código de función.", "Falta el parámetro fncod en POST");
        }
        if (!isset($_POST["fnrolest"])) {
            $this->throwError("Error: Falta el estado de la relación.", "Falta el parámetro fnrolest en POST");
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError("Error: Token de seguridad faltante.", "Falta el parámetro xsrtoken en POST");
        }

        if ($this->viewData["mode"] !== "INS") {
            if (intval($_POST["fnrolid"]) !== $this->viewData["fnrolid"]) {
                $this->throwError("Error: ID inconsistente.", "ID esperado: " . $this->viewData["fnrolid"] . " recibido: " . $_POST["fnrolid"]);
            }
        }

        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError("Error de seguridad: Token inválido.", "Token esperado: " . $_SESSION[$this->name . "-xsrtoken"] . " recibido: " . $_POST["xsrtoken"]);
        }

        $this->viewData["rolescod"] = trim($_POST["rolescod"]);
        $this->viewData["fncod"] = trim($_POST["fncod"]);
        $this->viewData["fnrolest"] = trim($_POST["fnrolest"]);
        $this->viewData["fnexp"] = $_POST["fnexp"] ?? null;
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["rolescod"])) {
            $this->innerError("rolescod", "Este campo es requerido.");
        }

        if (Validators::IsEmpty($this->viewData["fncod"])) {
            $this->innerError("fncod", "Este campo es requerido.");
        }

        if (Validators::IsEmpty($this->viewData["fnrolest"])) {
            $this->innerError("fnrolest", "Este campo es requerido.");
        } elseif (strlen($this->viewData["fnrolest"]) > 3) {
            $this->innerError("fnrolest", "Máximo 3 caracteres.");
        }

        return count($this->viewData["errors"]) === 0;
    }

    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "INS":
                if (FuncionesRolesDAO::insertFuncionRol(
                    $this->viewData["rolescod"],
                    $this->viewData["fncod"],
                    $this->viewData["fnrolest"],
                    $this->viewData["fnexp"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Relación creada correctamente.");
                } else {
                    $this->innerError("global", "Error al insertar la relación.");
                }
                break;
            case "UPD":
                if (FuncionesRolesDAO::updateFuncionRol(
                    $this->viewData["fnrolid"],
                    $this->viewData["rolescod"],
                    $this->viewData["fncod"],
                    $this->viewData["fnrolest"],
                    $this->viewData["fnexp"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Relación actualizada correctamente.");
                } else {
                    $this->innerError("global", "Error al actualizar la relación.");
                }
                break;
            case "DEL":
                if (FuncionesRolesDAO::deleteFuncionRol($this->viewData["fnrolid"]) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Relación eliminada correctamente.");
                } else {
                    $this->innerError("global", "Error al eliminar la relación.");
                }
                break;
        }
    }

    private function prepareViewData()
    {
        $this->viewData["modeDsc"] = sprintf(
            $this->modes[$this->viewData["mode"]],
            $this->viewData["rolescod"] . " - " . $this->viewData["fncod"]
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

        $this->viewData["fnrolest_ACT"] = $this->viewData["fnrolest"] === "ACT" ? "selected" : "";
        $this->viewData["fnrolest_INA"] = $this->viewData["fnrolest"] === "INA" ? "selected" : "";
        $this->viewData["fnrolest_PEN"] = $this->viewData["fnrolest"] === "PEN" ? "selected" : "";

        $this->viewData["timestamp"] = time();
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}
