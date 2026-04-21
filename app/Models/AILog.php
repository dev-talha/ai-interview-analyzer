<?php
namespace App\Models;

use App\Core\Model;

class AILog extends Model
{
    protected static string $table = 'ai_logs';

    public static function create(array $data): void
    {
        $stmt = self::db()->prepare('INSERT INTO ai_logs (session_id, question_id, prompt_text, ai_response, model_name) VALUES (:session_id, :question_id, :prompt_text, :ai_response, :model_name)');
        $stmt->execute($data);
    }
}
