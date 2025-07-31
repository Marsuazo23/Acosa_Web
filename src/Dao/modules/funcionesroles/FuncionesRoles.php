<?php

namespace Dao\Modules\FuncionesRoles;

use Dao\Table;

class FuncionesRoles extends Table
{
    public static function getAll(): array
    {
        $sqlstr = "SELECT fnrolid, rolescod, fncod, fnrolest, fnexp FROM funciones_roles;";
        return self::obtenerRegistros($sqlstr, []);
    }

    public static function getById(int $id)
    {
        $sqlstr = "SELECT * FROM funciones_roles WHERE fnrolid = :fnrolid;";
        return self::obtenerUnRegistro($sqlstr, ["fnrolid" => $id]);
    }

    public static function insertFuncionRol(
        string $rolescod,
        string $fncod,
        string $fnrolest,
        string $fnexp
    ) {
        $sqlstr = "INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp)
                VALUES (:rolescod, :fncod, :fnrolest, :fnexp);";
        return self::executeNonQuery(
            $sqlstr,
            [
                "rolescod" => $rolescod,
                "fncod" => $fncod,
                "fnrolest" => $fnrolest,
                "fnexp" => $fnexp
            ]
        );
    }

    public static function updateFuncionRol(
        int $id,
        string $rolescod,
        string $fncod,
        string $fnrolest,
        string $fnexp
    ) {
        $sqlstr = "UPDATE funciones_roles SET 
                    rolescod = :rolescod,
                    fncod = :fncod,
                    fnrolest = :fnrolest,
                    fnexp = :fnexp
                WHERE fnrolid = :fnrolid;";
        return self::executeNonQuery(
            $sqlstr,
            [
                "rolescod" => $rolescod,
                "fncod" => $fncod,
                "fnrolest" => $fnrolest,
                "fnexp" => $fnexp,
                "fnrolid" => $id
            ]
        );
    }

    public static function deleteFuncionRol(int $id)
    {
        $sqlstr = "DELETE FROM funciones_roles WHERE fnrolid = :fnrolid;";
        return self::executeNonQuery($sqlstr, ["fnrolid" => $id]);
    }

    public static function countAll(): int
    {
        $sqlstr = "SELECT COUNT(*) AS total FROM funciones_roles;";
        $row = self::obtenerUnRegistro($sqlstr, []);
        return $row ? intval($row["total"]) : 0;
    }

    public static function getPaged(int $itemsPerPage, int $offset): array
    {
        $sqlstr = "SELECT * FROM funciones_roles LIMIT :limit OFFSET :offset;";
        return self::obtenerRegistros(
            $sqlstr,
            [
                "limit" => $itemsPerPage,
                "offset" => $offset
            ],
        );
    }
}
