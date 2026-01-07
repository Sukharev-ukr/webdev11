<?php

class ApiController extends BaseController
{
    private MatchModel $matchModel;
    private Tournament $tournamentModel;

    public function __construct()
    {
        parent::__construct();
        $this->matchModel = new MatchModel();
        $this->tournamentModel = new Tournament();
    }

    public function matchesToday(): void
    {
        $matches = $this->matchModel->getToday();
        $this->json(['matches' => $matches]);
    }

    public function tournamentsUpcoming(): void
    {
        $tournaments = $this->tournamentModel->upcoming();
        $this->json(['tournaments' => $tournaments]);
    }
}

