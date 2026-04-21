<?php
namespace App\Core;

abstract class Model
{
    protected static string $table;

    public static function db(): \PDO
    {
        return Database::connection();
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function all(string $orderBy = 'id DESC'): array
    {
        $stmt = self::db()->query('SELECT * FROM ' . static::$table . ' ORDER BY ' . $orderBy);
        return $stmt->fetchAll();
    }
}
