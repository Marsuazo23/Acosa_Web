<?php

namespace Utilities;

class Nav
{
    public static function setPublicNavContext()
    {
        $tmpNAVIGATION = Context::getContextByKey("PUBLIC_NAVIGATION");
        if ($tmpNAVIGATION === "") {
            $navigationData = self::getNavFromJson()["public"];

            $navigationData = array_filter($navigationData, function ($item) {
                return $item["id"] !== "orders";
            });
            $navigationData = array_values($navigationData);

            $saveToSession = intval(Context::getContextByKey("DEVELOPMENT")) !== 1;
            Context::setContext("PUBLIC_NAVIGATION", $navigationData, $saveToSession);
        }
    }

    public static function setNavContext()
    {
        $tmpNAVIGATION = Context::getContextByKey("NAVIGATION");
        if (empty($tmpNAVIGATION)) {
            $tmpNAVIGATION = [];
            $userID = Security::getUserId();

            if ($userID === 0) {
            // Menú público sin la opción "orders"
            $tmpNAVIGATION = self::getNavFromJson()["public"];
            $tmpNAVIGATION = array_filter($tmpNAVIGATION, function($item) {
                return $item["id"] !== "orders";
            });
            $tmpNAVIGATION = array_values($tmpNAVIGATION);

            } else {
                // Verificar si es admin
                $roles = \Dao\Security\Security::getRolesByUsuario($userID);
                $isAdmin = in_array('admin', array_column($roles, 'rolescod'));

                if ($isAdmin) {
                    // Cargar todo el menú privado
                    $tmpNAVIGATION = self::getNavFromJson()["private"];
                } else {
                    // Usuario normal: validar permisos
                    $navigationData = self::getNavFromJson()["private"];
                    foreach ($navigationData as $navEntry) {
                        if (Security::isAuthorized($userID, $navEntry["id"], 'MNU')) {
                            $tmpNAVIGATION[] = $navEntry;
                        }
                    }

                    // Si no hay permisos privados, mostrar público sin login/signup
                    if (empty($tmpNAVIGATION)) {
                        $publicMenu = self::getNavFromJson()["public"];
                        $tmpNAVIGATION = array_filter($publicMenu, function($item) {
                            return !in_array($item["id"], ["Menu_SignIn", "Menu_SignUp"]);
                        });
                        $tmpNAVIGATION = array_values($tmpNAVIGATION);
                    }
                }
            }

            $saveToSession = intval(Context::getContextByKey("DEVELOPMENT")) !== 1;
            Context::setContext("NAVIGATION", $tmpNAVIGATION, $saveToSession);
        }
    }



    public static function invalidateNavData()
    {
        Context::removeContextByKey("NAVIGATION_DATA");
        Context::removeContextByKey("NAVIGATION");
        Context::removeContextByKey("PUBLIC_NAVIGATION");
    }

    private static function getNavFromJson()
    {
        $jsonContent = Context::getContextByKey("NAVIGATION_DATA");
        if ($jsonContent === "") {
            $filePath = 'nav.config.json';
            if (!file_exists($filePath)) {
                throw new \Exception(sprintf('%s does not exist', $filePath));
            }
            if (!is_readable($filePath)) {
                throw new \Exception(sprintf('%s file is not readable', $filePath));
            }
            $jsonContent = file_get_contents($filePath);
            $saveToSession = intval(Context::getContextByKey("DEVELOPMENT")) !== 1;
            Context::setContext("NAVIGATION_DATA", $jsonContent, $saveToSession);
        }
        $jsonData = json_decode($jsonContent, true);
        return $jsonData;
    }

    private function __construct()
    {
    }
    private function __clone()
    {
    }
}
