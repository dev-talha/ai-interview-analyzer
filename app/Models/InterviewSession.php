<?php
namespace App\Models;

use App\Core\Model;

class InterviewSession extends Model
{
    protected static string $table = 'interview_sessions';

    public static function create(array $data): int
    {
        $stmt = self::db()->prepare('INSERT INTO interview_sessions (user_id, category_id, total_questions, answered_questions, session_status) VALUES (:user_id, :category_id, :total_questions, 0, "started")');
        $stmt->execute($data);
        return (int) self::db()->lastInsertId();
    }

    public static function complete(int $id, array $data): void
    {
        $sql = 'UPDATE interview_sessions SET answered_questions=:answered_questions, overall_score=:overall_score, performance_level=:performance_level, final_feedback=:final_feedback, strengths=:strengths, weaknesses=:weaknesses, suggestions=:suggestions, session_status="completed" WHERE id=:id';
        $stmt = self::db()->prepare($sql);
        $stmt->execute($data + ['id' => $id]);
    }

    public static function forUser(int $userId): array
    {
        $sql = 'SELECT s.*, c.category_name FROM interview_sessions s JOIN categories c ON c.id = s.category_id WHERE s.user_id = :user_id ORDER BY s.id DESC';
        $stmt = self::db()->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public static function withRelations(): array
    {
        $sql = 'SELECT s.*, u.full_name, u.email, c.category_name FROM interview_sessions s JOIN users u ON u.id=s.user_id JOIN categories c ON c.id=s.category_id ORDER BY s.id DESC';
        return self::db()->query($sql)->fetchAll();
    }

    public static function showDetailed(int $id): ?array
    {
        $sql = 'SELECT s.*, u.full_name, u.email, c.category_name FROM interview_sessions s JOIN users u ON u.id=s.user_id JOIN categories c ON c.id=s.category_id WHERE s.id = :id LIMIT 1';
        $stmt = self::db()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function countAll(): int
    {
        return (int) self::db()->query('SELECT COUNT(*) FROM interview_sessions')->fetchColumn();
    }

    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare('DELETE FROM interview_sessions WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public static function paginateWithRelations(string $search = '', int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $sql = 'SELECT s.*, u.full_name, u.email, c.category_name FROM interview_sessions s JOIN users u ON u.id=s.user_id JOIN categories c ON c.id=s.category_id';
        $params = [];
        
        if ($search !== '') {
            $sql .= ' WHERE u.full_name LIKE :search OR u.email LIKE :search OR c.category_name LIKE :search OR s.session_status LIKE :search';
            $params['search'] = '%' . $search . '%';
        }
        
        $sql .= ' ORDER BY s.id DESC LIMIT ' . (int)$perPage . ' OFFSET ' . (int)$offset;
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        $countSql = 'SELECT COUNT(*) FROM interview_sessions s JOIN users u ON u.id=s.user_id JOIN categories c ON c.id=s.category_id';
        if ($search !== '') {
            $countSql .= ' WHERE u.full_name LIKE :search OR u.email LIKE :search OR c.category_name LIKE :search OR s.session_status LIKE :search';
            $countStmt = self::db()->prepare($countSql);
            $countStmt->execute($params);
            $total = (int) $countStmt->fetchColumn();
        } else {
            $total = self::countAll();
        }

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => ceil($total / $perPage) ?: 1,
        ];
    }
}
