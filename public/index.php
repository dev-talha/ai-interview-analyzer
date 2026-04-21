<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/app.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProfileController;
use App\Controllers\User\DashboardController;
use App\Controllers\User\InterviewController;
use App\Controllers\User\HistoryController;
use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\QuestionController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\ReportController;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/interview/start', [InterviewController::class, 'create']);
$router->post('/interview/start', [InterviewController::class, 'store']);
$router->get('/interview/show', [InterviewController::class, 'show']);
$router->post('/interview/submit', [InterviewController::class, 'submit']);
$router->post('/interview/transcribe', [InterviewController::class, 'transcribe']);
$router->get('/history', [HistoryController::class, 'index']);
$router->get('/history/view', [HistoryController::class, 'show']);
$router->get('/profile', [ProfileController::class, 'edit']);
$router->post('/profile', [ProfileController::class, 'update']);

$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/categories', [CategoryController::class, 'index']);
$router->post('/admin/categories/store', [CategoryController::class, 'store']);
$router->post('/admin/categories/update', [CategoryController::class, 'update']);
$router->post('/admin/categories/delete', [CategoryController::class, 'delete']);
$router->get('/admin/questions', [QuestionController::class, 'index']);
$router->post('/admin/questions/store', [QuestionController::class, 'store']);
$router->post('/admin/questions/update', [QuestionController::class, 'update']);
$router->post('/admin/questions/delete', [QuestionController::class, 'delete']);
$router->get('/admin/users', [UserController::class, 'index']);
$router->post('/admin/users/delete', [UserController::class, 'delete']);
$router->get('/admin/reports', [ReportController::class, 'index']);
$router->get('/admin/reports/view', [ReportController::class, 'show']);
$router->post('/admin/reports/delete', [ReportController::class, 'delete']);
$router->get('/admin/settings', [SettingsController::class, 'index']);
$router->post('/admin/settings', [SettingsController::class, 'update']);
$router->post('/admin/settings/test', [SettingsController::class, 'test']);
$router->post('/admin/settings/models', [SettingsController::class, 'models']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
