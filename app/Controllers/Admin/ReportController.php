<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Answer;
use App\Models\InterviewSession;

class ReportController extends Controller
{
    public function index(): void
    {
        requireAdmin();
        $search = trim($_GET['search'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $paginator = InterviewSession::paginateWithRelations($search, $page, 15);
        $this->render('admin/reports', [
            'sessions' => $paginator['data'],
            'search' => $search,
            'page' => $paginator['page'],
            'last_page' => $paginator['last_page']
        ]);
    }

    public function delete(): void
    {
        requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        InterviewSession::delete($id);
        flash('success', 'Report/Session deleted successfully.');
        redirect('/admin/reports');
    }

    public function show(): void
    {
        requireAdmin();
        $id = (int) ($_GET['id'] ?? 0);
        $this->render('user/report', [
            'session' => InterviewSession::showDetailed($id),
            'answers' => Answer::bySession($id),
        ]);
    }
}
