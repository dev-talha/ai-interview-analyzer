<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\Category;
use App\Models\InterviewSession;

class DashboardController extends Controller
{
    public function index(): void
    {
        requireAuth();
        if (isAdmin()) {
            redirect('/admin');
        }
        $sessions = InterviewSession::forUser((int) auth()['id']);
        $this->render('user/dashboard', [
            'categories' => Category::all('id ASC'),
            'sessions' => array_slice($sessions, 0, 5),
        ]);
    }
}
