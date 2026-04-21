<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\Category;
use App\Models\InterviewSession;
use App\Models\Question;
use App\Services\InterviewService;

class InterviewController extends Controller
{
    public function create(): void
    {
        requireAuth();
        $this->render('user/interview_start', ['categories' => Category::all('id ASC')]);
    }

    public function store(): void
    {
        requireAuth();
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $questions = Question::byCategory($categoryId, 5);
        if (!$questions) {
            flash('error', 'No active questions found in this category.');
            redirect('/interview/start');
        }
        $sessionId = InterviewSession::create([
            'user_id' => auth()['id'],
            'category_id' => $categoryId,
            'total_questions' => count($questions),
        ]);
        $_SESSION['interview_questions'][$sessionId] = $questions;
        redirect('/interview/show?session=' . $sessionId);
    }

    public function show(): void
    {
        requireAuth();
        $sessionId = (int) ($_GET['session'] ?? 0);
        $session = InterviewSession::find($sessionId);
        $questions = $_SESSION['interview_questions'][$sessionId] ?? [];
        if (!$session || !$questions) {
            flash('error', 'Interview session not found.');
            redirect('/dashboard');
        }
        $this->render('user/interview_show', ['session' => $session, 'questions' => $questions]);
    }

    public function submit(): void
    {
        requireAuth();
        $sessionId = (int) ($_POST['session_id'] ?? 0);
        $questions = $_SESSION['interview_questions'][$sessionId] ?? [];
        if (!$questions) {
            flash('error', 'Session expired. Please start again.');
            redirect('/dashboard');
        }
        (new InterviewService())->finalize($sessionId, $questions, $_POST['answers'] ?? []);
        unset($_SESSION['interview_questions'][$sessionId]);
        flash('success', 'Interview analyzed successfully.');
        redirect('/history/view?id=' . $sessionId);
    }

    public function transcribe(): void
    {
        requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['audio'])) {
            http_response_code(400);
            echo json_encode(['error' => 'No audio file provided.']);
            return;
        }

        $apiKey = config('groq_api_key', '');
        $baseUrl = config('groq_base_url', 'https://api.groq.com/openai/v1/audio/transcriptions');

        if (empty($apiKey)) {
            http_response_code(500);
            echo json_encode(['error' => 'Groq API Key is not configured in Admin Settings.']);
            return;
        }

        $fileTmpPath = $_FILES['audio']['tmp_name'];
        $fileName = $_FILES['audio']['name'];
        $mimeType = mime_content_type($fileTmpPath);

        $cfile = new \CURLFile($fileTmpPath, $mimeType, $fileName);

        $postData = [
            'file' => $cfile,
            'model' => 'whisper-large-v3'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: multipart/form-data'
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err || $httpCode !== 200) {
            http_response_code(500);
            echo json_encode(['error' => 'Groq AI Transcription Failed.', 'details' => $response]);
            return;
        }

        $data = json_decode($response, true);
        echo json_encode(['text' => $data['text'] ?? '']);
    }
}
