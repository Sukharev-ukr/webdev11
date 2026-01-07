<?php

class UserController extends BaseController
{
    private User $userModel;
    private Sport $sportModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->sportModel = new Sport();
    }

    public function profile(): void
    {
        $this->requireAuth();
        $authUser = current_user();
        $userId = (int)$authUser['id'];
        $user = $this->userModel->findById($userId);
        $sports = $this->sportModel->all();
        $userSports = $this->userModel->getSports($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? null)) {
                flash('error', 'Invalid CSRF token.');
                $this->redirect('user/profile');
            }

            $profileData = [
                'name' => trim($_POST['name'] ?? $user['name']),
                'city' => trim($_POST['city'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
            ];

            $this->userModel->updateProfile($userId, $profileData);

            if (!empty($_POST['password'])) {
                $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $this->userModel->updatePassword($userId, $hash);
            }

            $sportPayload = [];
            $sportIds = $_POST['sport_id'] ?? [];
            $skillLevels = $_POST['skill_level'] ?? [];

            foreach ($sportIds as $index => $sportId) {
                if (!$sportId) {
                    continue;
                }

                $sportPayload[] = [
                    'sport_id' => (int)$sportId,
                    'skill_level' => (int)($skillLevels[$index] ?? 1),
                    'preferred_position' => null,
                ];
            }

            $this->userModel->setSports($userId, $sportPayload);

            $_SESSION['user']['name'] = $profileData['name'];

            flash('success', 'Profile updated successfully.');
            $this->redirect('user/profile');
        }

        $this->render('user/profile', [
            'user' => $user,
            'sports' => $sports,
            'userSports' => $userSports,
        ]);
    }
}

