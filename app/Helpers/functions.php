<?php

declare(strict_types=1);

function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"");
        $_ENV[$key] = $value;
        putenv("{$key}={$value}");
    }
}

function env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

function config(string $key, mixed $default = null): mixed
{
    static $settings = null;
    if ($settings === null) {
        $settings = [];
        try {
            $settings = App\Models\Setting::keyValue();
        } catch (Throwable $e) {
            $settings = [];
        }
    }

    return $settings[$key] ?? env(strtoupper($key), $default);
}

function basePath(string $path = ''): string
{
    return __DIR__ . '/../../' . ltrim($path, '/');
}

function view(string $path, array $data = []): void
{
    extract($data);
    $view = basePath('resources/views/' . $path . '.php');
    require basePath('resources/views/layouts/app.php');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function flash(string $key, mixed $value): void
{
    $_SESSION['_flash'][$key] = $value;
}

function getFlash(string $key, mixed $default = null): mixed
{
    $value = $_SESSION['_flash'][$key] ?? $default;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function verify_csrf(): void
{
    $token = $_POST['_token'] ?? '';
    if (!hash_equals($_SESSION['_csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('Invalid CSRF token');
    }
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function auth(): ?array
{
    return $_SESSION['auth'] ?? null;
}

function isAdmin(): bool
{
    return (auth()['role'] ?? null) === 'admin';
}

function requireAuth(): void
{
    if (!auth()) {
        flash('error', 'Please login first.');
        redirect('/login');
    }
}

function requireAdmin(): void
{
    requireAuth();
    if (!isAdmin()) {
        flash('error', 'Access denied.');
        redirect('/dashboard');
    }
}

function formatDate(?string $date): string
{
    return $date ? date('d M Y, h:i A', strtotime($date)) : '-';
}
