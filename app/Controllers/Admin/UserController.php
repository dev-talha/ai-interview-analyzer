<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index(): void
    {
        requireAdmin();
        $search = trim($_GET['search'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $paginator = User::paginate($search, $page, 15);
        $this->render('admin/users', [
            'users' => $paginator['data'],
            'search' => $search,
            'page' => $paginator['page'],
            'last_page' => $paginator['last_page']
        ]);
    }

    public function delete(): void
    {
        requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        User::delete($id);
        flash('success', 'User deleted successfully.');
        redirect('/admin/users');
    }
}
