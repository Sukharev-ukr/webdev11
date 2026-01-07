<?php

class MatchesController extends BaseController
{
    private MatchModel $matchModel;
    private Sport $sportModel;
    private Location $locationModel;
    private MatchParticipant $participantModel;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->matchModel = new MatchModel();
        $this->sportModel = new Sport();
        $this->locationModel = new Location();
        $this->participantModel = new MatchParticipant();
        $this->userModel = new User();
    }

    public function index(): void
    {
        $filters = [];
        
        if (!empty($_GET['sport'])) {
            $filters['sport_id'] = (int)$_GET['sport'];
        }
        if (!empty($_GET['location'])) {
            $filters['location_id'] = (int)$_GET['location'];
        }
        if (!empty($_GET['date'])) {
            $filters['date'] = $_GET['date'];
        }

        $matches = $this->matchModel->getUpcoming($filters);
        $user = current_user();
        $userId = $user['id'] ?? null;

        foreach ($matches as &$match) {
            $participantCount = (int)($match['participant_count'] ?? 0);
            $maxPlayers = (int)($match['max_players'] ?? 0);
            $match['available_slots'] = max(0, $maxPlayers - $participantCount);
            $match['user_joined'] = $userId ? $this->participantModel->userIsInMatch((int)$match['id'], (int)$userId) : false;
        }

        if (isset($_GET['format']) && $_GET['format'] === 'json') {
            ob_start();
            $this->renderPartial('matches/partials/list', [
                'matches' => $matches,
                'currentUser' => $user,
            ]);
            $html = ob_get_clean();
            $this->json(['html' => $html]);
        }

        $this->render('matches/index', [
            'matches' => $matches,
            'sports' => $this->sportModel->all(),
            'locations' => $this->locationModel->all(),
            'currentUser' => $user,
        ]);
    }

    public function history(): void
    {
        $this->requireAuth();
        $user = current_user();
        $history = $this->userModel->getMatchHistory((int)$user['id']);

        $this->render('matches/history', [
            'history' => $history,
        ]);
    }

    public function join(): void
    {
        $this->requireAuth();
        $payload = $this->parseJsonOrForm();
        $token = $payload['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

        if (!verify_csrf($token)) {
            $this->json(['status' => 'error', 'message' => 'Invalid CSRF token'], 422);
            return;
        }

        $matchId = (int)($payload['match_id'] ?? 0);
        $match = $this->matchModel->find($matchId);

        if (!$match) {
            $this->json(['status' => 'error', 'message' => 'Match not found'], 404);
            return;
        }

        $user = current_user();
        $userId = (int)$user['id'];

        if ($this->participantModel->userIsInMatch($matchId, $userId)) {
            $this->json(['status' => 'error', 'message' => 'Already joined']);
            return;
        }

        if ($match['status'] !== 'open') {
            $this->json(['status' => 'error', 'message' => 'Match not open for joining']);
            return;
        }

        $participants = $this->matchModel->participantCount($matchId);
        if ($participants >= (int)$match['max_players']) {
            $this->matchModel->updateStatus($matchId, 'full');
            $this->json(['status' => 'error', 'message' => 'Match is already full']);
            return;
        }

        // Skill check removed - users can join/leave freely

        $this->participantModel->addParticipant($matchId, $userId);
        $participants++;

        if ($participants >= (int)$match['max_players']) {
            $this->matchModel->updateStatus($matchId, 'full');
        }

        $this->json([
            'status' => 'success',
            'message' => 'Joined match successfully.',
            'participants' => $participants,
        ]);
    }

    public function leave(): void
    {
        $this->requireAuth();
        $payload = $this->parseJsonOrForm();
        $token = $payload['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

        if (!verify_csrf($token)) {
            $this->json(['status' => 'error', 'message' => 'Invalid CSRF token'], 422);
            return;
        }

        $matchId = (int)($payload['match_id'] ?? 0);
        $match = $this->matchModel->find($matchId);

        if (!$match) {
            $this->json(['status' => 'error', 'message' => 'Match not found'], 404);
            return;
        }

        $user = current_user();
        $userId = (int)$user['id'];

        if (!$this->participantModel->userIsInMatch($matchId, $userId)) {
            $this->json(['status' => 'error', 'message' => 'You are not part of this match.']);
            return;
        }

        $this->participantModel->removeParticipant($matchId, $userId);
        $participants = $this->matchModel->participantCount($matchId);

        if ($match['status'] === 'full') {
            $this->matchModel->updateStatus($matchId, 'open');
        }

        $this->json([
            'status' => 'success',
            'message' => 'You have left the match.',
            'participants' => $participants,
        ]);
    }

    private function parseJsonOrForm(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $content = file_get_contents('php://input');
            $decoded = json_decode($content, true);
            return is_array($decoded) ? $decoded : [];
        }

        return $_POST;
    }
}

