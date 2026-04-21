<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Category;
use App\Models\InterviewSession;
use App\Models\Question;
use App\Models\User;

class AdminController extends Controller
{
    public function index(): void
    {
        requireAdmin();
        $this->render('admin/dashboard', [
            'userCount' => User::countAll(),
            'categoryCount' => Category::countAll(),
            'questionCount' => Question::countAll(),
            'sessionCount' => InterviewSession::countAll(),
            'sessions' => array_slice(InterviewSession::withRelations(), 0, 6),
        ]);
    }
}
