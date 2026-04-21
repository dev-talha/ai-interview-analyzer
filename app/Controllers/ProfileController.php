<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit(): void
    {
        requireAuth();
        $this->render('user/profile', ['user' => User::find((int) auth()['id'])]);
    }

    public function update(): void
    {
        requireAuth();
        User::updateProfile((int) auth()['id'], [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'gender' => $_POST['gender'] ?: null,
            'phone' => $_POST['phone'] ?: null,
        ]);
        $_SESSION['auth']['name'] = trim($_POST['full_name'] ?? auth()['name']);
        flash('success', 'Profile updated successfully.');
        redirect('/profile');
    }
}
