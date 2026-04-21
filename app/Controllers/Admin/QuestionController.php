<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Question;

class QuestionController extends Controller
{
    public function index(): void
    {
        requireAdmin();
        $this->render('admin/questions', [
            'questions' => Question::withCategory(),
            'categories' => Category::all('category_name ASC'),
        ]);
    }

    public function store(): void
    {
        requireAdmin();
        Question::create([
            'category_id' => (int) $_POST['category_id'],
            'question_text' => trim($_POST['question_text'] ?? ''),
            'difficulty' => $_POST['difficulty'] ?? 'medium',
            'status' => $_POST['status'] ?? 'active',
        ]);
        flash('success', 'Question created.');
        redirect('/admin/questions');
    }

    public function update(): void
    {
        requireAdmin();
        Question::updateById((int) $_POST['id'], [
            'category_id' => (int) $_POST['category_id'],
            'question_text' => trim($_POST['question_text'] ?? ''),
            'difficulty' => $_POST['difficulty'] ?? 'medium',
            'status' => $_POST['status'] ?? 'active',
        ]);
        flash('success', 'Question updated.');
        redirect('/admin/questions');
    }

    public function delete(): void
    {
        requireAdmin();
        Question::deleteById((int) $_POST['id']);
        flash('success', 'Question deleted.');
        redirect('/admin/questions');
    }
}
