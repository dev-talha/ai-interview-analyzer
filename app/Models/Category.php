<?php
namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    protected static string $table = 'categories';

    public static function create(array $data): void
    {
        $stmt = self::db()->prepare('INSERT INTO categories (category_name, description) VALUES (:category_name, :description)');
        $stmt->execute($data);
    }

    public static function updateById(int $id, array $data): void
    {
        $stmt = self::db()->prepare('UPDATE categories SET category_name=:category_name, description=:description WHERE id=:id');
        $stmt->execute($data + ['id' => $id]);
    }

    public static function deleteById(int $id): void
    {
        $stmt = self::db()->prepare('DELETE FROM categories WHERE id=:id');
        $stmt->execute(['id' => $id]);
    }

    public static function countAll(): int
    {
        return (int) self::db()->query('SELECT COUNT(*) FROM categories')->fetchColumn();
    }
}
