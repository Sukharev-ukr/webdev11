<?php

abstract class BaseController
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = base_path('views/' . $view . '.php');

        if (!file_exists($viewPath)) {
            throw new RuntimeException("View {$view} not found");
        }

        include base_path('views/layout/header.php');
        include $viewPath;
        include base_path('views/layout/footer.php');
    }

    protected function renderPartial(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = base_path('views/' . $view . '.php');

        if (!file_exists($viewPath)) {
            throw new RuntimeException("View {$view} not found");
        }

        include $viewPath;
    }

    protected function redirect(string $route): void
    {
        header('Location: index.php?route=' . $route);
        exit;
    }

    protected function requireAuth(): void
    {
        if (!current_user()) {
            flash('error', 'Please login to continue.');
            $this->redirect('auth/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireAuth();

        if (!is_admin()) {
            flash('error', 'Admin access required.');
            $this->redirect('home/index');
        }
    }

    protected function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit;
    }
}

