<?php

namespace Dao\modules\rolesusuarios;

use Dao\Table;

class RolesUsuarios extends Table
{
    public static function getAll(): array
    {
        $sqlstr = "SELECT roleuserid, usercod, rolescod, roleuserest, roleuserfch, roleuserexp 
                   FROM roles_usuarios;";
        return self::obtenerRegistros($sqlstr, []);
    }

    public static function getById(int $id)
    {
        $sqlstr = "SELECT * FROM roles_usuarios WHERE roleuserid = :roleuserid;";
        return self::obtenerUnRegistro($sqlstr, ["roleuserid" => $id]);
    }

    public static function insertRolesUsuario(
        int $usercod,
        string $rolescod,
        string $roleuserest,
        string $roleuserfch,
        string $roleuserexp
    ) {
        $sqlstr = "INSERT INTO roles_usuarios 
                    (usercod, rolescod, roleuserest, roleuserfch, roleuserexp)
                   VALUES 
                    (:usercod, :rolescod, :roleuserest, :roleuserfch, :roleuserexp);";
        return self::executeNonQuery(
            $sqlstr,
            [
                "usercod" => $usercod,
                "rolescod" => $rolescod,
                "roleuserest" => $roleuserest,
                "roleuserfch" => $roleuserfch,
                "roleuserexp" => $roleuserexp
            ]
        );
    }

    public static function updateRolesUsuario(
        int $id,
        int $usercod,
        string $rolescod,
        string $roleuserest,
        string $roleuserfch,
        string $roleuserexp
    ) {
        $sqlstr = "UPDATE roles_usuarios SET 
                    usercod = :usercod,
                    rolescod = :rolescod,
                    roleuserest = :roleuserest,
                    roleuserfch = :roleuserfch,
                    roleuserexp = :roleuserexp
                WHERE roleuserid = :roleuserid;";
        return self::executeNonQuery(
            $sqlstr,
            [
                "usercod" => $usercod,
                "rolescod" => $rolescod,
                "roleuserest" => $roleuserest,
                "roleuserfch" => $roleuserfch,
                "roleuserexp" => $roleuserexp,
                "roleuserid" => $id
            ]
        );
    }

    public static function deleteRolesUsuario(int $id)
    {
        $sqlstr = "DELETE FROM roles_usuarios WHERE roleuserid = :roleuserid;";
        return self::executeNonQuery($sqlstr, ["roleuserid" => $id]);
    }
}
