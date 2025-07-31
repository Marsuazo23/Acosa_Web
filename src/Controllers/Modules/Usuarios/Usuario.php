<?php

namespace Controllers\Modules\Usuarios;

use Controllers\PrivateController;
use Dao\modules\usuarios\Usuarios as UsuarioDAO;
use Views\Renderer;

use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Modules-Usuarios-Usuarios";

class Usuario extends PrivateController
{
    private array $viewData;
    private array $modes;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [
            "mode" => "",
            "usercod" => 0,
            "useremail" => "",
            "username" => "",
            "userpswd" => "", // solo para INS
            "userfching" => "",
            "userpswdest" => "",
            "userpswdexp" => "",
            "userest" => "",
            //"useractcod" => "", // no se mostrará
            "userpswdchg" => "",
            "usertipo" => "",
            "modeDsc" => "",
            "errors" => [],
            "cancelLabel" => "Cancelar",
            "showConfirm" => true,
            "readonly" => ""
        ];

        $this->modes = [
            "INS" => "Nuevo Usuario",
            "UPD" => "Actualizar Usuario: %s",
            "DEL" => "Eliminar Usuario: %s",
            "DSP" => "Detalles del Usuario: %s"
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

        Renderer::render("modules/usuarios/usuario", $this->viewData);
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
            $this->viewData["usercod"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmpUser = UsuarioDAO::getById($this->viewData["usercod"]);
        if ($tmpUser) {
            $this->viewData["useremail"] = $tmpUser["useremail"];
            $this->viewData["username"] = $tmpUser["username"];
            // NO cargar ni mostrar contraseña ni useractcod
            $this->viewData["userfching"] = $tmpUser["userfching"];
            $this->viewData["userpswdest"] = $tmpUser["userpswdest"];
            $this->viewData["userpswdexp"] = $tmpUser["userpswdexp"];
            $this->viewData["userest"] = $tmpUser["userest"];
            $this->viewData["userpswdchg"] = $tmpUser["userpswdchg"];
            $this->viewData["usertipo"] = $tmpUser["usertipo"];
        } else {
            $this->throwError("Error: Registro no encontrado.", "Usuario con id " . $this->viewData["usercod"] . " no existe.");
        }
    }

    private function getBodyData()
    {
        if (!isset($_POST["usercod"]) && $this->viewData["mode"] !== "INS") {
            $this->throwError("Error: Falta el campo ID en el formulario.", "Falta el parámetro usercod en POST");
        }
        if (!isset($_POST["useremail"])) {
            $this->throwError("Error: Falta el campo email.", "Falta el parámetro useremail en POST");
        }
        if (!isset($_POST["username"])) {
            $this->throwError("Error: Falta el campo nombre.", "Falta el parámetro username en POST");
        }
        if (!isset($_POST["userest"])) {
            $this->throwError("Error: Falta el estado del usuario.", "Falta el parámetro userest en POST");
        }
        if (!isset($_POST["userpswdest"])) {
            $this->throwError("Error: Falta el estado de la contraseña.", "Falta el parámetro userpswdest en POST");
        }
        if (!isset($_POST["usertipo"])) {
            $this->throwError("Error: Falta el tipo de usuario.", "Falta el parámetro usertipo en POST");
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError("Error: Token de seguridad faltante.", "Falta el parámetro xsrtoken en POST");
        }

        if ($this->viewData["mode"] !== "INS") {
            if (intval($_POST["usercod"]) !== $this->viewData["usercod"]) {
                $this->throwError("Error: ID inconsistente.", "ID esperado: " . $this->viewData["usercod"] . " recibido: " . $_POST["usercod"]);
            }
        }

        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError("Error de seguridad: Token inválido.", "Token esperado: " . $_SESSION[$this->name . "-xsrtoken"] . " recibido: " . $_POST["xsrtoken"]);
        }

        $this->viewData["useremail"] = trim($_POST["useremail"]);
        $this->viewData["username"] = trim($_POST["username"]);
        // Solo para INS, password se recibe y se encripta:
        if ($this->viewData["mode"] === "INS") {
            if (!isset($_POST["userpswd"]) || empty(trim($_POST["userpswd"]))) {
                $this->innerError("userpswd", "La contraseña es obligatoria para un nuevo usuario.");
            } else {
                $this->viewData["userpswd"] = password_hash(trim($_POST["userpswd"]), PASSWORD_DEFAULT);
            }
        }
        $this->viewData["userest"] = trim($_POST["userest"]);
        $this->viewData["userpswdest"] = trim($_POST["userpswdest"]);
        $this->viewData["usertipo"] = trim($_POST["usertipo"]);

        // Opcionales o no siempre requeridos:
        $this->viewData["userpswdexp"] = $_POST["userpswdexp"] ?? null;
        $this->viewData["userpswdchg"] = $_POST["userpswdchg"] ?? null;
        $this->viewData["userfching"] = $_POST["userfching"] ?? null;
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["useremail"])) {
            $this->innerError("useremail", "El email es obligatorio.");
        } elseif (!Validators::IsValidEmail($this->viewData["useremail"])) {
            $this->innerError("useremail", "Formato de email inválido.");
        } elseif (strlen($this->viewData["useremail"]) > 80) {
            $this->innerError("useremail", "Máximo 80 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["username"])) {
            $this->innerError("username", "El nombre es obligatorio.");
        } elseif (strlen($this->viewData["username"]) > 80) {
            $this->innerError("username", "Máximo 80 caracteres.");
        }

        if ($this->viewData["mode"] === "INS") {
            if (empty($this->viewData["userpswd"])) {
                $this->innerError("userpswd", "La contraseña es obligatoria.");
            }
        }

        if (Validators::IsEmpty($this->viewData["userest"])) {
            $this->innerError("userest", "El estado del usuario es obligatorio.");
        } elseif (strlen($this->viewData["userest"]) > 3) {
            $this->innerError("userest", "Máximo 3 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["userpswdest"])) {
            $this->innerError("userpswdest", "El estado de la contraseña es obligatorio.");
        } elseif (strlen($this->viewData["userpswdest"]) > 3) {
            $this->innerError("userpswdest", "Máximo 3 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["usertipo"])) {
            $this->innerError("usertipo", "El tipo de usuario es obligatorio.");
        } elseif (strlen($this->viewData["usertipo"]) > 3) {
            $this->innerError("usertipo", "Máximo 3 caracteres.");
        }

        return count($this->viewData["errors"]) === 0;
    }

    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "INS":
                // Aquí llamamos a Security::newUsuario, que recibe email y contraseña cruda
                try {
                    if (\Dao\Security\Security::newUsuario($this->viewData["useremail"], $_POST["userpswd"], $this->viewData["username"])) {
                        Site::redirectToWithMsg(LIST_URL, "Usuario creado correctamente.");
                    } else {
                        $this->innerError("global", "Error al insertar el usuario.");
                    }
                } catch (\Exception $ex) {
                    // Capturamos excepciones lanzadas por la validación o inserción
                    $this->innerError("global", "Error al crear usuario: " . $ex->getMessage());
                }
                break;

            case "UPD":
                // Aquí tu lógica de actualización (igual que antes)
                if (UsuarioDAO::updateUsuario(
                    $this->viewData["usercod"],
                    $this->viewData["useremail"],
                    $this->viewData["username"],
                    null, // password no se actualiza aquí
                    $this->viewData["userpswdest"],
                    $this->viewData["userpswdexp"],
                    $this->viewData["userest"],
                    $this->viewData["usertipo"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Usuario actualizado correctamente.");
                } else {
                    $this->innerError("global", "Error al actualizar el usuario.");
                }
                break;

            case "DEL":
                // Lógica eliminación (igual)
                if (UsuarioDAO::deleteUsuario($this->viewData["usercod"]) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Usuario eliminado correctamente.");
                } else {
                    $this->innerError("global", "Error al eliminar el usuario.");
                }
                break;
        }
    }

    private function prepareViewData()
    {
        $this->viewData["modeDsc"] = sprintf(
            $this->modes[$this->viewData["mode"]],
            $this->viewData["username"]
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
    } else {
        $this->viewData["readonly"] = "";
        $this->viewData["disabled"] = "";
    }

        $this->viewData["showPasswordField"] = ($this->viewData["mode"] === "INS");

        if ($this->viewData["mode"] !== "INS") {
            $this->viewData["userpswd"] = "**********";
            $this->viewData["userpswd_readonly"] = "readonly";  
            $this->viewData["showPasswordField"] = ""; 
        } else {
            $this->viewData["userpswd"] = "";
            $this->viewData["userpswd_readonly"] = "";         
            $this->viewData["showPasswordField"] = ""; 
        }

        $this->viewData["userpswdest_ACT"] = ($this->viewData["userpswdest"] === "ACT") ? "selected" : "";
        $this->viewData["userpswdest_INA"] = ($this->viewData["userpswdest"] === "INA") ? "selected" : "";
        $this->viewData["userpswdest_PEN"] = ($this->viewData["userpswdest"] === "PEN") ? "selected" : "";

        $this->viewData["userest_ACT"] = ($this->viewData["userest"] === "ACT") ? "selected" : "";
        $this->viewData["userest_INA"] = ($this->viewData["userest"] === "INA") ? "selected" : "";
        $this->viewData["userest_BLQ"] = ($this->viewData["userest"] === "BLQ") ? "selected" : "";
        $this->viewData["userest_SUS"] = ($this->viewData["userest"] === "SUS") ? "selected" : "";

        $this->viewData["usertipo_ADM"] = ($this->viewData["usertipo"] === "ADM") ? "selected" : "";
        $this->viewData["usertipo_PBL"] = ($this->viewData["usertipo"] === "PBL") ? "selected" : "";

        $this->viewData["timestamp"] = time();
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}
