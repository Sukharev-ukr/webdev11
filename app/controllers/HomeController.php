<?php

class HomeController extends BaseController
{
    public function index(): void
    {
        $matchModel = new MatchModel();
        $tournamentModel = new Tournament();
        $eventModel = new Event();
        $userModel = new User();

        $upcomingMatches = $matchModel->getUpcoming();
        $upcomingTournaments = array_slice($tournamentModel->upcoming(), 0, 5);
        $upcomingEvents = $eventModel->upcoming(3);
        $profile = null;

        if ($user = current_user()) {
            $profile = $userModel->findById((int)$user['id']);
        }

        // Debug: show first 5 matches
        $upcomingMatches = array_slice($upcomingMatches, 0, 5);

        $this->render('home/index', [
            'matches' => $upcomingMatches,
            'tournaments' => $upcomingTournaments,
            'events' => $upcomingEvents,
            'profile' => $profile,
        ]);
    }

    public function debug(): void
    {
        $db = Database::getConnection();
        
        // Check server time
        $now = $db->query("SELECT NOW() AS now, CURDATE() AS today")->fetch();
        echo "<h1>Debug: Database & Matches</h1>";
        echo "<h2>Server Time</h2>";
        echo "<pre>NOW(): " . $now['now'] . "\nCURDATE(): " . $now['today'] . "</pre>";
        
        // Raw query test
        $rawMatches = $db->query("
            SELECT m.id, m.date_time, m.status, 
                   s.name AS sport_name, l.name AS location_name
            FROM matches m
            LEFT JOIN sports s ON s.id = m.sport_id
            LEFT JOIN locations l ON l.id = m.location_id
            ORDER BY m.date_time ASC
            LIMIT 5
        ")->fetchAll();
        
        echo "<h2>Raw Query - First 5 Matches</h2>";
        echo "<pre>" . print_r($rawMatches, true) . "</pre>";
        
        // Test upcoming query
        $upcomingRaw = $db->query("
            SELECT m.id, m.date_time, m.status, 
                   s.name AS sport_name, l.name AS location_name
            FROM matches m
            LEFT JOIN sports s ON s.id = m.sport_id
            LEFT JOIN locations l ON l.id = m.location_id
            WHERE m.date_time >= NOW() AND m.status IN ('open', 'full')
            ORDER BY m.date_time ASC
        ")->fetchAll();
        
        echo "<h2>Upcoming Query Results (" . count($upcomingRaw) . ")</h2>";
        echo "<pre>" . print_r($upcomingRaw, true) . "</pre>";
        
        // Test via model
        $matchModel = new MatchModel();
        $upcomingMatches = $matchModel->getUpcoming();
        
        echo "<h2>Model getUpcoming() Results (" . count($upcomingMatches) . ")</h2>";
        echo "<pre>" . print_r($upcomingMatches, true) . "</pre>";
        
        $eventModel = new Event();
        $upcomingEvents = $eventModel->upcoming(10);
        echo "<h2>Upcoming Events (" . count($upcomingEvents) . ")</h2>";
        echo "<pre>" . print_r($upcomingEvents, true) . "</pre>";
    }
}

