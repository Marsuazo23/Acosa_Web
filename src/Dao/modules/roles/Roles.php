<?php

namespace Dao\modules\roles;

use Dao\Table;

class Roles extends Table
{
    // Obtener todos los registros
    public static function getAll(): array
    {
        $sqlstr = "SELECT roleid, rolescod, rolesdsc, rolesest FROM roles;";
        return self::obtenerRegistros($sqlstr, []);
    }

    // Obtener un registro por ID
    public static function getById(int $id)
    {
        $sqlstr = "SELECT * FROM roles WHERE roleid = :roleid;";
        return self::obtenerUnRegistro($sqlstr, ["roleid" => $id]);
    }

    // Insertar nuevo rol
    public static function insertRol(
        string $rolescod,
        string $rolesdsc,
        string $rolesest
    ) {
        $sqlstr = "INSERT INTO roles (rolescod, rolesdsc, rolesest)
                   VALUES (:rolescod, :rolesdsc, :rolesest);";
        return self::executeNonQuery(
            $sqlstr,
            [
                "rolescod" => $rolescod,
                "rolesdsc" => $rolesdsc,
                "rolesest" => $rolesest
            ]
        );
    }

    // Actualizar rol existente
    public static function updateRol(
        int $id,
        string $rolescod,
        string $rolesdsc,
        string $rolesest
    ) {
        $sqlstr = "UPDATE roles SET 
                        rolescod = :rolescod,
                        rolesdsc = :rolesdsc,
                        rolesest = :rolesest
                   WHERE roleid = :roleid;";
        return self::executeNonQuery(
            $sqlstr,
            [
                "rolescod" => $rolescod,
                "rolesdsc" => $rolesdsc,
                "rolesest" => $rolesest,
                "roleid"   => $id
            ]
        );
    }

    // Eliminar rol
    public static function deleteRol(int $id)
    {
        $sqlstr = "DELETE FROM roles WHERE roleid = :roleid;";
        return self::executeNonQuery($sqlstr, ["roleid" => $id]);
    }
}
