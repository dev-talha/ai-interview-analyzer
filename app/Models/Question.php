<?php
namespace App\Models;

use App\Core\Model;

class Question extends Model
{
    protected static string $table = 'questions';

    public static function create(array $data): void
    {
        $stmt = self::db()->prepare('INSERT INTO questions (category_id, question_text, difficulty, status) VALUES (:category_id, :question_text, :difficulty, :status)');
        $stmt->execute($data);
    }

    public static function updateById(int $id, array $data): void
    {
        $stmt = self::db()->prepare('UPDATE questions SET category_id=:category_id, question_text=:question_text, difficulty=:difficulty, status=:status WHERE id=:id');
        $stmt->execute($data + ['id' => $id]);
    }

    public static function deleteById(int $id): void
    {
        $stmt = self::db()->prepare('DELETE FROM questions WHERE id=:id');
        $stmt->execute(['id' => $id]);
    }

    public static function byCategory(int $categoryId, int $limit = 5): array
    {
        $stmt = self::db()->prepare('SELECT * FROM questions WHERE category_id = :category_id AND status = "active" ORDER BY RAND() LIMIT ' . (int) $limit);
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll();
    }

    public static function withCategory(): array
    {
        $sql = 'SELECT q.*, c.category_name FROM questions q JOIN categories c ON c.id = q.category_id ORDER BY q.id DESC';
        return self::db()->query($sql)->fetchAll();
    }

    public static function countAll(): int
    {
        return (int) self::db()->query('SELECT COUNT(*) FROM questions')->fetchColumn();
    }
}
