<?php

class TournamentController extends BaseController
{
    private Tournament $tournamentModel;
    private TournamentRound $roundModel;

    public function __construct()
    {
        parent::__construct();
        $this->tournamentModel = new Tournament();
        $this->roundModel = new TournamentRound();
    }

    public function index(): void
    {
        $tournaments = $this->tournamentModel->all();
        $rounds = [];

        foreach ($tournaments as $tournament) {
            $rounds[$tournament['id']] = $this->roundModel->roundsForTournament((int)$tournament['id']);
        }

        $this->render('tournaments/index', [
            'tournaments' => $tournaments,
            'rounds' => $rounds,
        ]);
    }
}

