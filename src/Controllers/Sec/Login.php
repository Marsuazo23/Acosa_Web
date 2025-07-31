<?php

namespace Controllers\Sec;

use Dao\Cart\Cart;
use Utilities\Cart\CartFns;

class Login extends \Controllers\PublicController
{
    private $txtEmail = "";
    private $txtPswd = "";
    private $errorEmail = "";
    private $errorPswd = "";
    private $generalError = "";
    private $hasError = false;

    public function run(): void
    {
        if ($this->isPostBack()) {
            $this->txtEmail = $_POST["txtEmail"];
            $this->txtPswd = $_POST["txtPswd"];

            // Validaciones
            if (!\Utilities\Validators::IsValidEmail($this->txtEmail)) {
                $this->errorEmail = "¡Correo no tiene el formato adecuado!";
                $this->hasError = true;
            }
            if (\Utilities\Validators::IsEmpty($this->txtPswd)) {
                $this->errorPswd = "¡Debe ingresar una contraseña!";
                $this->hasError = true;
            }

            if (!$this->hasError) {
                // Buscar usuario por email
                if ($dbUser = \Dao\Security\Security::getUsuarioByEmail($this->txtEmail)) {
                    // Validar estado de la cuenta
                    if ($dbUser["userest"] != \Dao\Security\Estados::ACTIVO) {
                        $this->generalError = "¡Credenciales son incorrectas!";
                        $this->hasError = true;
                        error_log(sprintf(
                            "ERROR: %d %s tiene cuenta con estado %s",
                            $dbUser["usercod"],
                            $dbUser["useremail"],
                            $dbUser["userest"]
                        ));
                    }

                    // Validar contraseña
                    if (!\Dao\Security\Security::verifyPassword($this->txtPswd, $dbUser["userpswd"])) {
                        $this->generalError = "¡Credenciales son incorrectas!";
                        $this->hasError = true;
                        error_log(sprintf(
                            "ERROR: %d %s contraseña incorrecta",
                            $dbUser["usercod"],
                            $dbUser["useremail"]
                        ));
                    }

                    // Si no hay errores, iniciar sesión
                    if (!$this->hasError) {
                        \Utilities\Security::login(
                            $dbUser["usercod"],
                            $dbUser["username"],
                            $dbUser["useremail"]
                        );

                        // *** Mover carrito anónimo al carrito del usuario ***
                        $anoncod = CartFns::getAnnonCartCode();
                        Cart::moveAnonToAuth($anoncod, $dbUser["usercod"]);

                        \Utilities\Nav::invalidateNavData();
                        \Utilities\Nav::setNavContext();

                        // Redirigir a página previa o al home
                        if (\Utilities\Context::getContextByKey("redirto") !== "") {
                            \Utilities\Site::redirectTo(
                                \Utilities\Context::getContextByKey("redirto")
                            );
                        } else {
                            \Utilities\Site::redirectTo("index.php");
                        }
                    }
                } else {
                    // Usuario no encontrado
                    $this->generalError = "¡Credenciales son incorrectas!";
                    error_log(sprintf("ERROR: %s trato de ingresar", $this->txtEmail));
                }
            }
        }

        // Pasar datos a la vista
        $dataView = get_object_vars($this);
        \Views\Renderer::render("security/login", $dataView);
    }
}