<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(): void
    {
        requireAdmin();
        $this->render('admin/categories', ['categories' => Category::all('id DESC')]);
    }

    public function store(): void
    {
        requireAdmin();
        Category::create([
            'category_name' => trim($_POST['category_name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ]);
        flash('success', 'Category created.');
        redirect('/admin/categories');
    }

    public function update(): void
    {
        requireAdmin();
        Category::updateById((int) $_POST['id'], [
            'category_name' => trim($_POST['category_name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ]);
        flash('success', 'Category updated.');
        redirect('/admin/categories');
    }

    public function delete(): void
    {
        requireAdmin();
        Category::deleteById((int) $_POST['id']);
        flash('success', 'Category removed.');
        redirect('/admin/categories');
    }
}
