<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected static string $table = 'users';

    public static function create(array $data): int
    {
        $stmt = self::db()->prepare('INSERT INTO users (full_name, email, password, role, gender, phone) VALUES (:full_name, :email, :password, :role, :gender, :phone)');
        $stmt->execute($data);
        return (int) self::db()->lastInsertId();
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public static function updateProfile(int $id, array $data): void
    {
        $stmt = self::db()->prepare('UPDATE users SET full_name=:full_name, gender=:gender, phone=:phone WHERE id=:id');
        $stmt->execute($data + ['id' => $id]);
    }

    public static function countAll(): int
    {
        return (int) self::db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public static function paginate(string $search = '', int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $sql = 'SELECT * FROM users';
        $params = [];
        if ($search !== '') {
            $sql .= ' WHERE full_name LIKE :search OR email LIKE :search';
            $params['search'] = '%' . $search . '%';
        }
        $sql .= ' ORDER BY id DESC LIMIT ' . (int)$perPage . ' OFFSET ' . (int)$offset;
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        // count total
        $countSql = 'SELECT COUNT(*) FROM users';
        if ($search !== '') {
            $countSql .= ' WHERE full_name LIKE :search OR email LIKE :search';
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
