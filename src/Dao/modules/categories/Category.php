<?php

namespace Dao\modules\categories;

use Dao\Table;

class Category extends Table
{
    // Obtener todas las categorías
    public static function getAll(): array
    {
        $sqlstr = "SELECT categoryId, categoryName FROM categories;";
        return self::obtenerRegistros($sqlstr, []);
    }

    // Obtener una categoría por su ID
    public static function getById(int $id)
    {
        $sqlstr = "SELECT * FROM categories WHERE categoryId = :categoryId;";
        return self::obtenerUnRegistro($sqlstr, ["categoryId" => $id]);
    }

    // Insertar nueva categoría
    public static function insertCategory(string $categoryName)
    {
        $sqlstr = "INSERT INTO categories (categoryName)
                   VALUES (:categoryName);";
        return self::executeNonQuery(
            $sqlstr,
            [
                "categoryName" => $categoryName
            ]
        );
    }

    // Actualizar categoría
    public static function updateCategory(int $id, string $categoryName)
    {
        $sqlstr = "UPDATE categories SET 
                      categoryName = :categoryName
                   WHERE categoryId = :categoryId;";
        return self::executeNonQuery(
            $sqlstr,
            [
                "categoryName" => $categoryName,
                "categoryId"   => $id
            ]
        );
    }

    // Eliminar categoría
    public static function deleteCategory(int $id)
    {
        $sqlstr = "DELETE FROM categories WHERE categoryId = :categoryId;";
        return self::executeNonQuery($sqlstr, ["categoryId" => $id]);
    }
}
