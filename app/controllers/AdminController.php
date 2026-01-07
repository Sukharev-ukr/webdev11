<?php

class AdminController extends BaseController
{
    private Sport $sportModel;
    private Location $locationModel;
    private MatchModel $matchModel;
    private MatchParticipant $participantModel;
    private Tournament $tournamentModel;
    private Event $eventModel;

    public function __construct()
    {
        parent::__construct();
        $this->sportModel = new Sport();
        $this->locationModel = new Location();
        $this->matchModel = new MatchModel();
        $this->participantModel = new MatchParticipant();
        $this->tournamentModel = new Tournament();
        $this->eventModel = new Event();
    }

    public function dashboard(): void
    {
        $this->requireAdmin();

        $stats = [
            'users' => (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'matches' => (int)$this->db->query("SELECT COUNT(*) FROM matches")->fetchColumn(),
            'tournaments' => (int)$this->db->query("SELECT COUNT(*) FROM tournaments")->fetchColumn(),
        ];

        $this->render('admin/dashboard', [
            'stats' => $stats,
            'matches' => array_slice($this->matchModel->getUpcoming(), 0, 10),
            'tournaments' => $this->tournamentModel->upcoming(),
        ]);
    }

    public function sports(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->guardCsrf();
            $action = $_POST['form_action'] ?? 'create';
            $sportId = (int)($_POST['id'] ?? 0);
            $payload = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'] ?? ''),
            ];

            if ($action === 'delete' && $sportId) {
                $this->sportModel->delete($sportId);
            } elseif ($sportId) {
                $this->sportModel->update($sportId, $payload);
            } else {
                $this->sportModel->create($payload);
            }

            flash('success', 'Sport changes saved.');
            $this->redirect('admin/sports');
        }

        $this->render('admin/sports', [
            'sports' => $this->sportModel->all(),
        ]);
    }

    public function locations(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->guardCsrf();
            $action = $_POST['form_action'] ?? 'create';
            $locationId = (int)($_POST['id'] ?? 0);
            $payload = [
                'name' => trim($_POST['name']),
                'address' => trim($_POST['address'] ?? ''),
                'city' => trim($_POST['city'] ?? ''),
            ];

            if ($action === 'delete' && $locationId) {
                $this->locationModel->delete($locationId);
            } elseif ($locationId) {
                $this->locationModel->update($locationId, $payload);
            } else {
                $this->locationModel->create($payload);
            }

            flash('success', 'Location changes saved.');
            $this->redirect('admin/locations');
        }

        $this->render('admin/locations', [
            'locations' => $this->locationModel->all(),
        ]);
    }

    public function matches(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->guardCsrf();
            $action = $_POST['form_action'] ?? 'create';
            $matchId = (int)($_POST['id'] ?? 0);

            // Handle delete separately (doesn't need payload)
            if ($action === 'delete' && $matchId) {
                $this->matchModel->delete($matchId);
                flash('success', 'Match deleted successfully.');
                $this->redirect('admin/matches');
                return;
            }

            // Build payload for create/update
            $payload = [
                'sport_id' => (int)($_POST['sport_id'] ?? 0),
                'location_id' => (int)($_POST['location_id'] ?? 0),
                'creator_id' => (int)current_user()['id'],
                'date_time' => $_POST['date_time'] ?? '',
                'max_players' => (int)($_POST['max_players'] ?? 10),
                'min_skill_level' => (int)($_POST['min_skill_level'] ?? 1),
                'max_skill_level' => (int)($_POST['max_skill_level'] ?? 5),
                'status' => $_POST['status'] ?? 'open',
                'tournament_id' => !empty($_POST['tournament_id']) ? (int)$_POST['tournament_id'] : null,
            ];

            if ($matchId) {
                $this->matchModel->update($matchId, $payload);
                flash('success', 'Match updated successfully.');
            } else {
                $this->matchModel->create($payload);
                flash('success', 'Match created successfully.');
            }

            $this->redirect('admin/matches');
        }

        $matches = $this->matchModel->getUpcoming();
        $participants = [];
        foreach ($matches as $match) {
            $participants[$match['id']] = $this->participantModel->participantsForMatch((int)$match['id']);
        }

        $this->render('admin/matches', [
            'matches' => $matches,
            'participants' => $participants,
            'sports' => $this->sportModel->all(),
            'locations' => $this->locationModel->all(),
            'tournaments' => $this->tournamentModel->all(),
        ]);
    }

    public function tournaments(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->guardCsrf();
            $action = $_POST['form_action'] ?? 'create';
            $tournamentId = (int)($_POST['id'] ?? 0);

            // Handle delete separately (doesn't need payload)
            if ($action === 'delete' && $tournamentId) {
                $this->tournamentModel->delete($tournamentId);
                flash('success', 'Tournament deleted successfully.');
                $this->redirect('admin/tournaments');
                return;
            }

            // Build payload for create/update
            $payload = [
                'sport_id' => (int)($_POST['sport_id'] ?? 0),
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'start_date' => $_POST['start_date'] ?? null,
                'end_date' => $_POST['end_date'] ?? null,
                'status' => $_POST['status'] ?? 'upcoming',
            ];

            if ($tournamentId) {
                $this->tournamentModel->update($tournamentId, $payload);
                flash('success', 'Tournament updated successfully.');
            } else {
                $this->tournamentModel->create($payload);
                flash('success', 'Tournament created successfully.');
            }

            $this->redirect('admin/tournaments');
        }

        $this->render('admin/tournaments', [
            'tournaments' => $this->tournamentModel->all(),
            'sports' => $this->sportModel->all(),
        ]);
    }

    public function events(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->guardCsrf();
            $action = $_POST['form_action'] ?? 'create';
            $eventId = (int)($_POST['id'] ?? 0);

            // Handle delete separately (doesn't need payload)
            if ($action === 'delete' && $eventId) {
                $this->eventModel->delete($eventId);
                flash('success', 'Event deleted successfully.');
                $this->redirect('admin/events');
                return;
            }

            // Build payload for create/update
            $payload = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'start_at' => $this->normalizeDateTime($_POST['start_at'] ?? null),
                'venue' => trim($_POST['venue'] ?? ''),
                'city' => trim($_POST['city'] ?? ''),
                'link' => trim($_POST['link'] ?? ''),
            ];

            if ($eventId) {
                $this->eventModel->update($eventId, $payload);
                flash('success', 'Event updated successfully.');
            } else {
                $this->eventModel->create($payload, (int)current_user()['id']);
                flash('success', 'Event created successfully.');
            }

            $this->redirect('admin/events');
        }

        $this->render('admin/events', [
            'events' => $this->eventModel->all(),
        ]);
    }

    public function approveResult(): void
    {
        $this->requireAdmin();
        $payload = $this->parseJsonOrForm();
        $token = $payload['csrf_token'] ?? null;

        if (!verify_csrf($token)) {
            $this->json(['status' => 'error', 'message' => 'Invalid CSRF token'], 422);
            return;
        }

        $participantId = (int)($payload['participant_id'] ?? 0);
        $result = $payload['result'] ?? 'none';

        $validResults = ['win', 'loss', 'none'];
        if (!in_array($result, $validResults, true)) {
            $this->json(['status' => 'error', 'message' => 'Invalid result value'], 422);
            return;
        }

        $this->participantModel->updateResult($participantId, $result);
        $this->json(['status' => 'success', 'message' => 'Result updated.']);
        return;
    }

    private function guardCsrf(): void
    {
        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid CSRF token.');
            $this->redirect('admin/dashboard');
        }
    }

    private function parseJsonOrForm(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $content = file_get_contents('php://input');
            $data = json_decode($content, true);
            return is_array($data) ? $data : [];
        }

        return $_POST;
    }

    private function normalizeDateTime(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $normalized = str_replace('T', ' ', trim($value));
        if (!preg_match('/:\d{2}$/', $normalized)) {
            $normalized .= ':00';
        }

        return $normalized;
    }
}

