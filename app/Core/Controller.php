<?php
namespace App\Core;

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        view($view, $data);
    }

    protected function back(string $fallback = '/'): never
    {
        redirect($_SERVER['HTTP_REFERER'] ?? $fallback);
    }
}
