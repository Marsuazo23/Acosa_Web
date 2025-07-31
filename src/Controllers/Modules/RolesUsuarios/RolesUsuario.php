<?php

namespace Controllers\Modules\RolesUsuarios;

use Controllers\PrivateController;
use Dao\modules\rolesusuarios\RolesUsuarios as RolesUsuariosDAO;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Modules-RolesUsuarios-RolesUsuarios";

class RolesUsuario extends PrivateController
{
    private array $viewData;
    private array $modes;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [
            "mode" => "",
            "roleuserid" => 0,
            "usercod" => 0,
            "rolescod" => "",
            "roleuserest" => "",
            "roleuserfch" => "",
            "roleuserexp" => "",
            "modeDsc" => "",
            "errors" => [],
            "cancelLabel" => "Cancelar",
            "showConfirm" => true,
            "readonly" => ""
        ];

        $this->modes = [
            "INS" => "Nuevo Rol de Usuario",
            "UPD" => "Actualizar Rol de Usuario: %s",
            "DEL" => "Eliminar Rol de Usuario: %s",
            "DSP" => "Detalles del Rol de Usuario: %s"
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

        Renderer::render("modules/rolesusuarios/rolesusuario", $this->viewData);
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
            $this->viewData["roleuserid"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmp = RolesUsuariosDAO::getById($this->viewData["roleuserid"]);
        if ($tmp) {
            $this->viewData["usercod"] = $tmp["usercod"];
            $this->viewData["rolescod"] = $tmp["rolescod"];
            $this->viewData["roleuserest"] = $tmp["roleuserest"];
            $this->viewData["roleuserfch"] = $tmp["roleuserfch"];
            $this->viewData["roleuserexp"] = $tmp["roleuserexp"];
        } else {
            $this->throwError(
                "Error: Registro no encontrado.",
                "RolesUsuario con id " . $this->viewData["roleuserid"] . " no existe."
            );
        }
    }

    private function getBodyData()
    {
        if (!isset($_POST["roleuserid"])) {
            $this->throwError("Error: Falta el campo ID en el formulario.", "Falta el parámetro roleuserid en POST");
        }
        if (!isset($_POST["usercod"])) {
            $this->throwError("Error: Falta el campo usuario.", "Falta el parámetro usercod en POST");
        }
        if (!isset($_POST["rolescod"])) {
            $this->throwError("Error: Falta el campo rol.", "Falta el parámetro rolescod en POST");
        }
        if (!isset($_POST["roleuserest"])) {
            $this->throwError("Error: Falta el campo estado.", "Falta el parámetro roleuserest en POST");
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError("Error: Token de seguridad faltante.", "Falta el parámetro xsrtoken en POST");
        }

        if (intval($_POST["roleuserid"]) !== $this->viewData["roleuserid"]) {
            $this->throwError(
                "Error: ID inconsistente.",
                "ID esperado: " . $this->viewData["roleuserid"] . " recibido: " . $_POST["roleuserid"]
            );
        }

        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError(
                "Error de seguridad: Token inválido.",
                "Token esperado: " . $_SESSION[$this->name . "-xsrtoken"] . " recibido: " . $_POST["xsrtoken"]
            );
        }

        $this->viewData["usercod"] = intval($_POST["usercod"]);
        $this->viewData["rolescod"] = trim($_POST["rolescod"]);
        $this->viewData["roleuserest"] = trim($_POST["roleuserest"]);
        $this->viewData["roleuserfch"] = $_POST["roleuserfch"] ?? null;
        $this->viewData["roleuserexp"] = $_POST["roleuserexp"] ?? null;
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["usercod"])) {
            $this->innerError("usercod", "Este campo es requerido.");
        } elseif (!is_numeric($this->viewData["usercod"])) {
            $this->innerError("usercod", "Debe ser un número válido.");
        }

        if (Validators::IsEmpty($this->viewData["rolescod"])) {
            $this->innerError("rolescod", "Este campo es requerido.");
        }

        if (Validators::IsEmpty($this->viewData["roleuserest"])) {
            $this->innerError("roleuserest", "Este campo es requerido.");
        }
        if (strlen($this->viewData["roleuserest"]) > 3) {
            $this->innerError("roleuserest", "Máximo 3 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["roleuserfch"])) {
            $this->innerError("roleuserfch", "La fecha de asignación es requerida.");
        }

        if (Validators::IsEmpty($this->viewData["roleuserexp"])) {
            $this->innerError("roleuserexp", "La fecha de expiración es requerida.");
        }

        return count($this->viewData["errors"]) === 0;
    }

    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "INS":
                if (RolesUsuariosDAO::insertRolesUsuario(
                    $this->viewData["usercod"],
                    $this->viewData["rolescod"],
                    $this->viewData["roleuserest"],
                    $this->viewData["roleuserfch"],
                    $this->viewData["roleuserexp"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Rol de usuario creado correctamente.");
                } else {
                    $this->innerError("global", "Error al insertar el rol de usuario.");
                }
                break;
            case "UPD":
                if (RolesUsuariosDAO::updateRolesUsuario(
                    $this->viewData["roleuserid"],
                    $this->viewData["usercod"],
                    $this->viewData["rolescod"],
                    $this->viewData["roleuserest"],
                    $this->viewData["roleuserfch"],
                    $this->viewData["roleuserexp"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Rol de usuario actualizado correctamente.");
                } else {
                    $this->innerError("global", "Error al actualizar el rol de usuario.");
                }
                break;
            case "DEL":
                if (RolesUsuariosDAO::deleteRolesUsuario($this->viewData["roleuserid"]) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Rol de usuario eliminado correctamente.");
                } else {
                    $this->innerError("global", "Error al eliminar el rol de usuario.");
                }
                break;
        }
    }

    private function prepareViewData()
    {
        $this->viewData["modeDsc"] = sprintf(
            $this->modes[$this->viewData["mode"]],
            $this->viewData["rolescod"]
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

        $this->viewData["roleuserest_ACT"] = ($this->viewData["roleuserest"] === "ACT") ? "selected" : "";
        $this->viewData["roleuserest_INA"] = ($this->viewData["roleuserest"] === "INA") ? "selected" : "";
        $this->viewData["roleuserest_PEN"] = ($this->viewData["roleuserest"] === "PEN") ? "selected" : "";

        $this->viewData["timestamp"] = time();
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}
