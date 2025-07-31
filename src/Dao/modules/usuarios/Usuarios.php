<?php

namespace Dao\modules\usuarios;

use Dao\Table;

class Usuarios extends Table
{
    public static function getAll(): array
    {
        // Obtenemos todos los usuarios sin mostrar contraseña ni useractcod
        $sqlstr = "SELECT 
            usercod,
            useremail,
            username,
            userfching,
            userpswdest,
            userpswdexp,
            userest,
            usertipo
            FROM usuario;";
        return self::obtenerRegistros($sqlstr, []);
    }

    public static function getById(int $id)
    {
        $sqlstr = "SELECT 
            usercod,
            useremail,
            username,
            userfching,
            userpswdest,
            userpswdexp,
            userest,
            userpswdchg,   
            usertipo
            FROM usuario WHERE usercod = :usercod;";
        return self::obtenerUnRegistro($sqlstr, ["usercod" => $id]);
    }

    public static function insertUsuario(
        string $useremail,
        string $username,
        string $userpswd,
        string $userfching,
        string $userpswdest,
        ?string $userpswdexp,
        string $userest,
        string $useractcod,
        string $userpswdchg,
        string $usertipo
    ) {
        $sqlstr = "INSERT INTO usuario (
                useremail, username, userpswd, userfching, userpswdest, userpswdexp, userest, useractcod, userpswdchg, usertipo
            ) VALUES (
                :useremail, :username, :userpswd, :userfching, :userpswdest, :userpswdexp, :userest, :useractcod, :userpswdchg, :usertipo
            );";

        return self::executeNonQuery(
            $sqlstr,
            [
                "useremail" => $useremail,
                "username" => $username,
                "userpswd" => $userpswd,
                "userfching" => $userfching,
                "userpswdest" => $userpswdest,
                "userpswdexp" => $userpswdexp,
                "userest" => $userest,
                "useractcod" => $useractcod,
                "userpswdchg" => $userpswdchg,
                "usertipo" => $usertipo
            ]
        );
    }

    public static function updateUsuario(
        int $usercod,
        string $useremail,
        string $username,
        ?string $userpswd,     // si es null no se actualiza
        string $userpswdest,
        ?string $userpswdexp,
        string $userest,
        string $usertipo
    ) {
        $params = [
            "usercod" => $usercod,
            "useremail" => $useremail,
            "username" => $username,
            "userpswdest" => $userpswdest,
            "userpswdexp" => $userpswdexp,
            "userest" => $userest,
            "usertipo" => $usertipo
        ];

        $sqlstr = "UPDATE usuario SET 
                    useremail = :useremail,
                    username = :username,
                    userpswdest = :userpswdest,
                    userpswdexp = :userpswdexp,
                    userest = :userest,
                    usertipo = :usertipo";

        if (!empty($userpswd)) {
            $sqlstr .= ", userpswd = :userpswd";
            $params["userpswd"] = $userpswd;
        }

        $sqlstr .= " WHERE usercod = :usercod;";

        return self::executeNonQuery($sqlstr, $params);
    }

    public static function deleteUsuario(int $usercod)
    {
        $sqlstr = "DELETE FROM usuario WHERE usercod = :usercod;";
        return self::executeNonQuery($sqlstr, ["usercod" => $usercod]);
    }
}
