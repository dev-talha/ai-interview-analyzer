<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->render('auth/login');
    }

    public function showRegister(): void
    {
        $this->render('auth/register');
    }

    public function register(): void
    {
        $_SESSION['_old'] = $_POST;
        $name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            flash('error', 'Please fill all fields correctly.');
            redirect('/register');
        }

        if (User::findByEmail($email)) {
            flash('error', 'Email already exists.');
            redirect('/register');
        }

        $id = User::create([
            'full_name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
            'gender' => $_POST['gender'] ?: null,
            'phone' => $_POST['phone'] ?: null,
        ]);

        $_SESSION['auth'] = ['id' => $id, 'name' => $name, 'email' => $email, 'role' => 'user'];
        flash('success', 'Registration successful. Welcome aboard.');
        redirect('/dashboard');
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            flash('error', 'Invalid credentials.');
            redirect('/login');
        }

        $_SESSION['auth'] = ['id' => $user['id'], 'name' => $user['full_name'], 'email' => $user['email'], 'role' => $user['role']];
        flash('success', 'Welcome back, ' . $user['full_name'] . '.');
        redirect($user['role'] === 'admin' ? '/admin' : '/dashboard');
    }

    public function logout(): void
    {
        unset($_SESSION['auth']);
        session_regenerate_id(true);
        flash('success', 'Logged out successfully.');
        redirect('/');
    }
}
