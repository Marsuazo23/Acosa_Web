<?php

namespace Dao\modules\funciones;

use Dao\Table;

class Funciones extends Table
{
    public static function getById(int $id)
    {
        $sqlstr = "SELECT * FROM funciones WHERE fnid = :fnid;";
        return self::obtenerUnRegistro($sqlstr, ["fnid" => $id]);
    }

    public static function insertFuncion(
        string $fncod,
        ?string $fndsc,
        ?string $fnest,
        ?string $fntyp
    ) {
        $sqlstr = "INSERT INTO funciones (fncod, fndsc, fnest, fntyp)
                   VALUES (:fncod, :fndsc, :fnest, :fntyp);";
        return self::executeNonQuery(
            $sqlstr,
            [
                "fncod" => $fncod,
                "fndsc" => $fndsc,
                "fnest" => $fnest,
                "fntyp" => $fntyp
            ]
        );
    }

    public static function updateFuncion(
        int $id,
        string $fncod,
        ?string $fndsc,
        ?string $fnest,
        ?string $fntyp
    ) {
        $sqlstr = "UPDATE funciones SET
                    fncod = :fncod,
                    fndsc = :fndsc,
                    fnest = :fnest,
                    fntyp = :fntyp
                   WHERE fnid = :fnid;";
        return self::executeNonQuery(
            $sqlstr,
            [
                "fncod" => $fncod,
                "fndsc" => $fndsc,
                "fnest" => $fnest,
                "fntyp" => $fntyp,
                "fnid" => $id
            ]
        );
    }

    public static function deleteFuncion(int $id)
    {
        $sqlstr = "DELETE FROM funciones WHERE fnid = :fnid;";
        return self::executeNonQuery($sqlstr, ["fnid" => $id]);
    }

    public static function countAll(): int
    {
        $sqlstr = "SELECT COUNT(*) as total FROM funciones;";
        $result = self::obtenerUnRegistro($sqlstr, []);
        return $result ? intval($result["total"]) : 0;
    }

    public static function getPaged(int $limit, int $offset): array
    {
        $sqlstr = "SELECT fnid, fncod, fndsc, fnest, fntyp 
                FROM funciones 
                LIMIT :offset, :limit;";
        return self::obtenerRegistros(
            $sqlstr,
            [
                "offset" => $offset,
                "limit"  => $limit
            ]
        );
    }
}
