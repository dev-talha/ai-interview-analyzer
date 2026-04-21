<?php
namespace App\Models;

use App\Core\Model;

class Answer extends Model
{
    protected static string $table = 'answers';

    public static function create(array $data): void
    {
        $sql = 'INSERT INTO answers (session_id, question_id, answer_text, relevance_score, clarity_score, confidence_score, professionalism_score, total_score, feedback, suggestion) VALUES (:session_id, :question_id, :answer_text, :relevance_score, :clarity_score, :confidence_score, :professionalism_score, :total_score, :feedback, :suggestion)';
        $stmt = self::db()->prepare($sql);
        $stmt->execute($data);
    }

    public static function bySession(int $sessionId): array
    {
        $sql = 'SELECT a.*, q.question_text FROM answers a JOIN questions q ON q.id = a.question_id WHERE a.session_id = :session_id ORDER BY a.id ASC';
        $stmt = self::db()->prepare($sql);
        $stmt->execute(['session_id' => $sessionId]);
        return $stmt->fetchAll();
    }
}
