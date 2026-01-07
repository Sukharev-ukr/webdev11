<?php

class AuthController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? null)) {
                flash('error', 'Invalid CSRF token.');
                $this->redirect('auth/login');
            }

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = $this->userModel->findByEmail($email);

            if (!$user || !$this->passwordMatches($password, $user)) {
                flash('error', 'Invalid credentials.');
                $this->redirect('auth/login');
            }

            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'role' => $user['role'],
            ];

            flash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');
            
            // Redirect admins to dashboard
            if ($user['role'] === 'admin') {
                $this->redirect('admin/dashboard');
            } else {
                $this->redirect('home/index');
            }
        }

        $this->render('auth/login');
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? null)) {
                flash('error', 'Invalid CSRF token.');
                $this->redirect('auth/register');
            }

            $password = $_POST['password'] ?? '';
            if (strlen($password) < 6) {
                flash('error', 'Password must be at least 6 characters.');
                $this->redirect('auth/register');
            }

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'player',
                'city' => trim($_POST['city'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
            ];

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                flash('error', 'Please provide a valid email address.');
                $this->redirect('auth/register');
            }

            if ($this->userModel->findByEmail($data['email'])) {
                flash('error', 'Email already registered.');
                $this->redirect('auth/register');
            }

            $userId = $this->userModel->create($data);
            $_SESSION['user'] = [
                'id' => $userId,
                'name' => $data['name'],
                'role' => 'player',
            ];

            flash('success', 'Account created successfully.');
            $this->redirect('home/index');
        }

        $this->render('auth/register');
    }

    public function logout(): void
    {
        session_destroy();
        session_start();
        flash('success', 'You have been logged out.');
        $this->redirect('home/index');
    }

    private function passwordMatches(string $password, array $user): bool
    {
        if (password_verify($password, $user['password_hash'])) {
            return true;
        }

        if ($this->isLegacyMysqlHash($user['password_hash']) && $this->verifyLegacyMysqlHash($password, $user['password_hash'])) {
            $this->userModel->updatePassword((int)$user['id'], password_hash($password, PASSWORD_DEFAULT));
            return true;
        }

        return false;
    }

    private function isLegacyMysqlHash(string $hash): bool
    {
        return strlen($hash) === 41 && str_starts_with($hash, '*');
    }

    private function verifyLegacyMysqlHash(string $password, string $hash): bool
    {
        if (!$this->isLegacyMysqlHash($hash)) {
            return false;
        }

        $stage1 = sha1($password, true);
        $stage2 = sha1($stage1);
        $calculated = '*' . strtoupper($stage2);

        return hash_equals($hash, $calculated);
    }
}

