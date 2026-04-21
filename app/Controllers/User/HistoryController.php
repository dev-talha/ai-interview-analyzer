<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\Answer;
use App\Models\InterviewSession;

class HistoryController extends Controller
{
    public function index(): void
    {
        requireAuth();
        $this->render('user/history', ['sessions' => InterviewSession::forUser((int) auth()['id'])]);
    }

    public function show(): void
    {
        requireAuth();
        $id = (int) ($_GET['id'] ?? 0);
        $session = InterviewSession::showDetailed($id);
        if (!$session || ((int) $session['user_id'] !== (int) auth()['id'] && !isAdmin())) {
            flash('error', 'Report not found.');
            redirect('/history');
        }
        $this->render('user/report', ['session' => $session, 'answers' => Answer::bySession($id)]);
    }
}
