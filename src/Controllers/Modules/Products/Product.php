<?php

namespace Controllers\Modules\Products;

use Controllers\PrivateController;
use Dao\modules\products\Products as ProductsDAO;
use Views\Renderer;

use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Modules-Products-Products";

class Product extends PrivateController
{
    private array $viewData;
    private array $modes;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [
            "mode" => "",
            "productId" => 0,
            "productName" => "",
            "productDescription" => "",
            "productPrice" => 0.0,
            "productImgUrl" => "",
            "productStatus" => "",
            "categoryId" => 0,
            "productStock" => 0,
            "modeDsc" => "",
            "errors" => [],
            "cancelLabel" => "Cancelar",
            "showConfirm" => true,
            "readonly" => ""
        ];

        $this->modes = [
            "INS" => "Nuevo Producto",
            "UPD" => "Actualizar Producto: %s",
            "DEL" => "Eliminar Producto: %s",
            "DSP" => "Detalles del Producto: %s"
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

        Renderer::render("modules/products/product", $this->viewData);
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
            $this->viewData["productId"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmpProduct = ProductsDAO::getById($this->viewData["productId"]);
        if ($tmpProduct) {
            $this->viewData["productName"] = $tmpProduct["productName"];
            $this->viewData["productDescription"] = $tmpProduct["productDescription"];
            $this->viewData["productPrice"] = $tmpProduct["productPrice"];
            $this->viewData["productImgUrl"] = $tmpProduct["productImgUrl"];
            $this->viewData["productStatus"] = $tmpProduct["productStatus"];
            $this->viewData["categoryId"] = $tmpProduct["categoryId"];
            $this->viewData["productStock"] = $tmpProduct["productStock"];
        } else {
            $this->throwError("Error: Registro no encontrado.", "Producto con id " . $this->viewData["productId"] . " no existe.");
        }
    }

    private function getBodyData()
    {
        if (!isset($_POST["productId"])) {
            $this->throwError("Error: Falta el campo ID en el formulario.", "Falta el parámetro productId en POST");
        }
        if (!isset($_POST["productName"])) {
            $this->throwError("Error: Falta el campo nombre del producto.", "Falta el parámetro productName en POST");
        }
        if (!isset($_POST["productDescription"])) {
            $this->throwError("Error: Falta la descripción del producto.", "Falta el parámetro productDescription en POST");
        }
        if (!isset($_POST["productPrice"])) {
            $this->throwError("Error: Falta el precio del producto.", "Falta el parámetro productPrice en POST");
        }
        if (!isset($_POST["productImgUrl"])) {
            $this->throwError("Error: Falta la URL de la imagen.", "Falta el parámetro productImgUrl en POST");
        }
        if (!isset($_POST["productStatus"])) {
            $this->throwError("Error: Falta el estado del producto.", "Falta el parámetro productStatus en POST");
        }
        if (!isset($_POST["categoryId"])) {
            $this->throwError("Error: Falta la categoría del producto.", "Falta el parámetro categoryId en POST");
        }
        if (!isset($_POST["productStock"])) {
            $this->throwError("Error: Falta el productStock del producto.", "Falta el parámetro productStock en POST");
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError("Error: Token de seguridad faltante.", "Falta el parámetro xsrtoken en POST");
        }

        if (intval($_POST["productId"]) !== $this->viewData["productId"]) {
            $this->throwError("Error: ID inconsistente.", "ID esperado: " . $this->viewData["productId"] . " recibido: " . $_POST["productId"]);
        }

        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError("Error de seguridad: Token inválido.", "Token esperado: " . $_SESSION[$this->name . "-xsrtoken"] . " recibido: " . $_POST["xsrtoken"]);
        }

        $this->viewData["productName"] = trim($_POST["productName"]);
        $this->viewData["productDescription"] = trim($_POST["productDescription"]);
        $this->viewData["productPrice"] = floatval($_POST["productPrice"]);
        $this->viewData["productImgUrl"] = trim($_POST["productImgUrl"]);
        $this->viewData["productStatus"] = trim($_POST["productStatus"]);
        $this->viewData["categoryId"] = intval($_POST["categoryId"]);
        $this->viewData["productStock"] = intval($_POST["productStock"]);
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["productName"])) {
            $this->innerError("productName", "Este campo es requerido.");
        }
        if (strlen($this->viewData["productName"]) > 255) {
            $this->innerError("productName", "Máximo 255 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["productDescription"])) {
            $this->innerError("productDescription", "Este campo es requerido.");
        }

        if (Validators::IsEmpty($this->viewData["productPrice"])) {
            $this->innerError("productPrice", "Este campo es requerido.");
        } elseif (!is_numeric($this->viewData["productPrice"]) || $this->viewData["productPrice"] <= 0) {
            $this->innerError("productPrice", "Debe ser un número mayor que cero.");
        }

        if (Validators::IsEmpty($this->viewData["productImgUrl"])) {
            $this->innerError("productImgUrl", "Este campo es requerido.");
        }
        if (strlen($this->viewData["productImgUrl"]) > 255) {
            $this->innerError("productImgUrl", "Máximo 255 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["productStatus"])) {
            $this->innerError("productStatus", "Este campo es requerido.");
        }
        if (strlen($this->viewData["productStatus"]) > 3) {
            $this->innerError("productStatus", "Máximo 3 caracteres.");
        }

        if (Validators::IsEmpty($this->viewData["categoryId"])) {
            $this->innerError("categoryId", "Este campo es requerido.");
        } elseif (!is_numeric($this->viewData["categoryId"]) || $this->viewData["categoryId"] < 1) {
            $this->innerError("categoryId", "Debe ser un número válido.");
        }

        if (Validators::IsEmpty($this->viewData["productStock"])) {
            $this->innerError("productStock", "Este campo es requerido.");
        } elseif (!is_numeric($this->viewData["productStock"]) || $this->viewData["productStock"] < 0) {
            $this->innerError("productStock", "Debe ser un número mayor o igual a cero.");
        }

        return count($this->viewData["errors"]) === 0;
    }

    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "INS":
                if (ProductsDAO::insertProduct(
                    $this->viewData["productName"],
                    $this->viewData["productDescription"],
                    $this->viewData["productPrice"],
                    $this->viewData["productImgUrl"],
                    $this->viewData["productStatus"],
                    $this->viewData["categoryId"],
                    $this->viewData["productStock"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Producto creado correctamente.");
                } else {
                    $this->innerError("global", "Error al insertar el producto.");
                }
                break;
            case "UPD":
                if (ProductsDAO::updateProduct(
                    $this->viewData["productId"],
                    $this->viewData["productName"],
                    $this->viewData["productDescription"],
                    $this->viewData["productPrice"],
                    $this->viewData["productImgUrl"],
                    $this->viewData["productStatus"],
                    $this->viewData["categoryId"],
                    $this->viewData["productStock"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Producto actualizado correctamente.");
                } else {
                    $this->innerError("global", "Error al actualizar el producto.");
                }
                break;
            case "DEL":
                if (ProductsDAO::deleteProduct($this->viewData["productId"]) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Producto eliminado correctamente.");
                } else {
                    $this->innerError("global", "Error al eliminar el producto.");
                }
                break;
        }
    }

    private function prepareViewData()
    {
        $this->viewData["modeDsc"] = sprintf(
            $this->modes[$this->viewData["mode"]],
            $this->viewData["productName"]
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

        // Opciones del select para productStatus
        $this->viewData["productStatus_ACT"] = $this->viewData["productStatus"] === "ACT" ? "selected" : "";
        $this->viewData["productStatus_INA"] = $this->viewData["productStatus"] === "INA" ? "selected" : "";
        $this->viewData["productStatus_PEN"] = $this->viewData["productStatus"] === "PEN" ? "selected" : "";

        $this->viewData["timestamp"] = time();
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}
