<?php
namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    protected static string $table = 'settings';

    public static function keyValue(): array
    {
        try {
            $rows = self::db()->query('SELECT setting_key, setting_value FROM settings')->fetchAll();
        } catch (\Throwable $e) {
            return [];
        }
        $out = [];
        foreach ($rows as $row) {
            $out[$row['setting_key']] = $row['setting_value'];
        }
        return $out;
    }

    public static function upsertMany(array $settings): void
    {
        $stmt = self::db()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:setting_key, :setting_value) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        foreach ($settings as $key => $value) {
            $stmt->execute(['setting_key' => $key, 'setting_value' => $value]);
        }
    }
}
