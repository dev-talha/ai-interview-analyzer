<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Setting;
use App\Services\OllamaService;

class SettingsController extends Controller
{
    public function index(): void
    {
        requireAdmin();
        $this->render('admin/settings', ['settings' => Setting::keyValue()]);
    }

    public function update(): void
    {
        requireAdmin();
        Setting::upsertMany([
            'ollama_mode' => trim($_POST['ollama_mode'] ?? 'local'),
            'ollama_api_key' => trim($_POST['ollama_api_key'] ?? ''),
            'ollama_enabled' => isset($_POST['ollama_enabled']) ? 'true' : 'false',
            'ollama_base_url' => trim($_POST['ollama_base_url'] ?? ''),
            'ollama_model' => trim($_POST['ollama_model'] ?? ''),
            'ollama_timeout' => trim($_POST['ollama_timeout'] ?? '60'),
            'ollama_temperature' => trim($_POST['ollama_temperature'] ?? '0.2'),
            'ollama_system_prompt' => trim($_POST['ollama_system_prompt'] ?? ''),
            'stt_approach' => trim($_POST['stt_approach'] ?? 'browser'),
            'groq_api_key' => trim($_POST['groq_api_key'] ?? ''),
            'groq_base_url' => trim($_POST['groq_base_url'] ?? 'https://api.groq.com/openai/v1/audio/transcriptions'),
        ]);
        flash('success', 'AI settings saved.');
        redirect('/admin/settings');
    }

    public function test(): void
    {
        requireAdmin();
        $result = (new OllamaService())->testConnection();
        flash($result['ok'] ? 'success' : 'error', $result['ok'] ? 'Ollama connection successful.' : 'Ollama test failed: ' . ($result['error'] ?: 'HTTP ' . $result['status']));
        redirect('/admin/settings');
    }

    public function models(): void
    {
        requireAdmin();
        $result = (new OllamaService())->getModels();
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}
