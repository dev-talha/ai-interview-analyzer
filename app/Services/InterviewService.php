<?php
namespace App\Services;

use App\Models\Answer;
use App\Models\InterviewSession;

class InterviewService
{
    public function finalize(int $sessionId, array $questions, array $answers): void
    {
        $ollama = new OllamaService();
        $allScores = [];
        $strengths = [];
        $weaknesses = [];
        $suggestions = [];

        foreach ($questions as $question) {
            $answerText = trim($answers[$question['id']] ?? '');
            $result = $ollama->analyzeAnswer($sessionId, (int) $question['id'], $question['question_text'], $answerText);
            $allScores[] = $result['total_score'];
            $strengths[] = 'Q' . $question['id'] . ': ' . $result['feedback'];
            $weaknesses[] = 'Q' . $question['id'] . ': Improve clarity or structure where needed.';
            $suggestions[] = 'Q' . $question['id'] . ': ' . $result['suggestion'];

            Answer::create([
                'session_id' => $sessionId,
                'question_id' => $question['id'],
                'answer_text' => $answerText,
                'relevance_score' => $result['relevance_score'],
                'clarity_score' => $result['clarity_score'],
                'confidence_score' => $result['confidence_score'],
                'professionalism_score' => $result['professionalism_score'],
                'total_score' => $result['total_score'],
                'feedback' => $result['feedback'],
                'suggestion' => $result['suggestion'],
            ]);
        }

        $overall = count($allScores) ? round((array_sum($allScores) / (count($allScores) * 40)) * 100, 2) : 0;
        $level = $overall >= 80 ? 'Excellent' : ($overall >= 65 ? 'Good' : ($overall >= 50 ? 'Average' : 'Needs Improvement'));

        InterviewSession::complete($sessionId, [
            'answered_questions' => count($questions),
            'overall_score' => $overall,
            'performance_level' => $level,
            'final_feedback' => "Overall performance is {$level}. The candidate showed promise with opportunities to improve structure, specificity, and confidence.",
            'strengths' => implode("\n", $strengths),
            'weaknesses' => implode("\n", $weaknesses),
            'suggestions' => implode("\n", $suggestions),
        ]);
    }
}
