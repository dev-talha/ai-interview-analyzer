<?php
namespace App\Services;

use App\Models\AILog;

class OllamaService
{
    public function analyzeAnswer(int $sessionId, int $questionId, string $question, string $answer): array
    {
        $payload = [
            'model' => config('ollama_model', env('OLLAMA_MODEL', 'llama3.2:3b')),
            'format' => 'json',
            'stream' => false,
            'options' => [
                'temperature' => (float) config('ollama_temperature', env('OLLAMA_TEMPERATURE', '0.2')),
            ],
            'prompt' => $this->buildPrompt($question, $answer),
        ];

        $responseText = '';
        $parsed = null;

        if (filter_var(config('ollama_enabled', env('OLLAMA_ENABLED', 'true')), FILTER_VALIDATE_BOOL)) {
            $headers = ['Content-Type: application/json'];
            if (config('ollama_mode', 'local') === 'cloud' && config('ollama_api_key', '')) {
                $headers[] = 'Authorization: Bearer ' . config('ollama_api_key', '');
            }
            
            $url = rtrim((string) config('ollama_base_url', env('OLLAMA_BASE_URL', 'http://host.docker.internal:11434')), '/') . '/api/generate';
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
                CURLOPT_TIMEOUT => (int) config('ollama_timeout', env('OLLAMA_TIMEOUT', '60')),
            ]);
            $result = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($result !== false) {
                $decoded = json_decode($result, true);
                $responseText = $decoded['response'] ?? $result;
                $parsed = json_decode($responseText, true);
            }

            if ($parsed === null) {
                $parsed = $this->fallbackScores($question, $answer, $error ?: 'Fallback scoring applied because AI response could not be parsed.');
            }
        } else {
            $parsed = $this->fallbackScores($question, $answer, 'Ollama disabled from admin settings.');
            $responseText = json_encode($parsed, JSON_PRETTY_PRINT);
        }

        AILog::create([
            'session_id' => $sessionId,
            'question_id' => $questionId,
            'prompt_text' => $payload['prompt'],
            'ai_response' => $responseText ?: json_encode($parsed, JSON_PRETTY_PRINT),
            'model_name' => $payload['model'],
        ]);

        return $this->normalize($parsed);
    }

    public function testConnection(): array
    {
        $url = rtrim((string) config('ollama_base_url', env('OLLAMA_BASE_URL', 'http://host.docker.internal:11434')), '/') . '/api/tags';
        $ch = curl_init($url);
        
        $options = [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10];
        if (config('ollama_mode', 'local') === 'cloud' && config('ollama_api_key', '')) {
            $options[CURLOPT_HTTPHEADER] = ['Authorization: Bearer ' . config('ollama_api_key', '')];
        }
        curl_setopt_array($ch, $options);
        
        $result = curl_exec($ch);
        $error = curl_error($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'ok' => $result !== false && $status >= 200 && $status < 300,
            'status' => $status,
            'body' => $result,
            'error' => $error,
        ];
    }

    public function getModels(): array
    {
        $url = rtrim((string) config('ollama_base_url', env('OLLAMA_BASE_URL', 'http://host.docker.internal:11434')), '/') . '/api/tags';
        $ch = curl_init($url);
        
        $options = [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10];
        if (config('ollama_mode', 'local') === 'cloud' && config('ollama_api_key', '')) {
            $options[CURLOPT_HTTPHEADER] = ['Authorization: Bearer ' . config('ollama_api_key', '')];
        }
        curl_setopt_array($ch, $options);
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        if ($result !== false) {
            $decoded = json_decode($result, true);
            if (isset($decoded['models']) && is_array($decoded['models'])) {
                return array_map(function($m) { return $m['name']; }, $decoded['models']);
            }
        }
        return [];
    }

    private function buildPrompt(string $question, string $answer): string
    {
        $defaultPrompt = "You are an interview evaluator. Review the candidate answer and return only valid JSON with these keys: relevance_score, clarity_score, confidence_score, professionalism_score, feedback, suggestion. Scores must be numbers between 0 and 10. Keep feedback and suggestion concise.";
        $systemPrompt = config('ollama_system_prompt', $defaultPrompt);
        if (trim($systemPrompt) === '') {
            $systemPrompt = $defaultPrompt;
        }
        return "{$systemPrompt}\n\nQuestion: {$question}\n\nCandidate Answer: {$answer}";
    }

    private function normalize(array $data): array
    {
        $scores = [];
        foreach (['relevance_score', 'clarity_score', 'confidence_score', 'professionalism_score'] as $key) {
            $scores[$key] = max(0, min(10, (float) ($data[$key] ?? 5)));
        }
        $scores['feedback'] = trim((string) ($data['feedback'] ?? 'Solid answer with room for improvement.'));
        $scores['suggestion'] = trim((string) ($data['suggestion'] ?? 'Use a more structured STAR-style response.'));
        $scores['total_score'] = array_sum(array_intersect_key($scores, array_flip(['relevance_score', 'clarity_score', 'confidence_score', 'professionalism_score'])));
        return $scores;
    }

    private function fallbackScores(string $question, string $answer, string $reason): array
    {
        $wordCount = str_word_count(strip_tags($answer));
        $relevance = str_word_count($answer) >= 12 ? 7 : 5;
        $clarity = $wordCount >= 20 ? 7 : 5;
        $confidence = preg_match('/\b(can|will|have|led|built|solved|improved)\b/i', $answer) ? 7 : 5;
        $professionalism = preg_match('/\b(thank|team|project|experience|responsible)\b/i', $answer) ? 7 : 6;
        return [
            'relevance_score' => $relevance,
            'clarity_score' => $clarity,
            'confidence_score' => $confidence,
            'professionalism_score' => $professionalism,
            'feedback' => 'Automated fallback evaluation was used. ' . $reason,
            'suggestion' => 'Add clearer examples, measurable outcomes, and a more professional structure.',
        ];
    }
}
