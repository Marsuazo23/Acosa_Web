<?php

namespace Controllers\Modules\Roles;

use Controllers\PrivateController;
use Dao\modules\roles\Roles as RolesDAO;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Modules-Roles-Roles";

class Rol extends PrivateController
{
    private array $viewData;
    private array $modes;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [
            "mode" => "",
            "roleid" => 0,
            "rolescod" => "",
            "rolesdsc" => "",
            "rolesest" => "",
            "modeDsc" => "",
            "errors" => [],
            "cancelLabel" => "Cancelar",
            "showConfirm" => true,
            "readonly" => ""
        ];

        $this->modes = [
            "INS" => "Nuevo Rol",
            "UPD" => "Actualizar Rol: %s",
            "DEL" => "Eliminar Rol: %s",
            "DSP" => "Detalles del Rol: %s"
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
        Renderer::render("modules/roles/rol", $this->viewData);
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
            $this->viewData["roleid"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmpRol = RolesDAO::getById($this->viewData["roleid"]);
        if ($tmpRol) {
            $this->viewData["rolescod"] = $tmpRol["rolescod"];
            $this->viewData["rolesdsc"] = $tmpRol["rolesdsc"];
            $this->viewData["rolesest"] = $tmpRol["rolesest"];
        } else {
            $this->throwError("Error: Registro no encontrado.", "Rol con id " . $this->viewData["roleid"] . " no existe.");
        }
    }

    private function getBodyData()
    {
        if (!isset($_POST["roleid"])) {
            $this->throwError("Error: Falta el campo ID en el formulario.", "Falta el parámetro roleid en POST");
        }
        if (!isset($_POST["rolescod"])) {
            $this->throwError("Error: Falta el código del rol.", "Falta el parámetro rolescod en POST");
        }
        if (!isset($_POST["rolesdsc"])) {
            $this->throwError("Error: Falta la descripción del rol.", "Falta el parámetro rolesdsc en POST");
        }
        if (!isset($_POST["rolesest"])) {
            $this->throwError("Error: Falta el estado del rol.", "Falta el parámetro rolesest en POST");
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError("Error: Token de seguridad faltante.", "Falta el parámetro xsrtoken en POST");
        }

        if (intval($_POST["roleid"]) !== $this->viewData["roleid"]) {
            $this->throwError("Error: ID inconsistente.", "ID esperado: " . $this->viewData["roleid"] . " recibido: " . $_POST["roleid"]);
        }

        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError("Error de seguridad: Token inválido.", "Token esperado: " . $_SESSION[$this->name . "-xsrtoken"] . " recibido: " . $_POST["xsrtoken"]);
        }

        $this->viewData["rolescod"] = trim($_POST["rolescod"]);
        $this->viewData["rolesdsc"] = trim($_POST["rolesdsc"]);
        $this->viewData["rolesest"] = trim($_POST["rolesest"]);
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["rolescod"])) {
            $this->innerError("rolescod", "Este campo es requerido.");
        }
        if (strlen($this->viewData["rolescod"]) > 128) {
            $this->innerError("rolescod", "Máximo 128 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["rolesdsc"])) {
            $this->innerError("rolesdsc", "Este campo es requerido.");
        }
        if (strlen($this->viewData["rolesdsc"]) > 45) {
            $this->innerError("rolesdsc", "Máximo 45 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["rolesest"])) {
            $this->innerError("rolesest", "Este campo es requerido.");
        }
        if (strlen($this->viewData["rolesest"]) > 3) {
            $this->innerError("rolesest", "Máximo 3 caracteres.");
        }

        return count($this->viewData["errors"]) === 0;
    }

    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "INS":
                if (RolesDAO::insertRol(
                    $this->viewData["rolescod"],
                    $this->viewData["rolesdsc"],
                    $this->viewData["rolesest"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Rol creado correctamente.");
                } else {
                    $this->innerError("global", "Error al insertar el rol.");
                }
                break;
            case "UPD":
                if (RolesDAO::updateRol(
                    $this->viewData["roleid"],
                    $this->viewData["rolescod"],
                    $this->viewData["rolesdsc"],
                    $this->viewData["rolesest"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Rol actualizado correctamente.");
                } else {
                    $this->innerError("global", "Error al actualizar el rol.");
                }
                break;
            case "DEL":
                if (RolesDAO::deleteRol($this->viewData["roleid"]) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Rol eliminado correctamente.");
                } else {
                    $this->innerError("global", "Error al eliminar el rol.");
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

        $this->viewData["rolesest_ACT"] = $this->viewData["rolesest"] === "ACT" ? "selected" : "";
        $this->viewData["rolesest_INA"] = $this->viewData["rolesest"] === "INA" ? "selected" : "";
        $this->viewData["rolesest_PEN"] = $this->viewData["rolesest"] === "PEN" ? "selected" : "";

        $this->viewData["timestamp"] = time();
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}
