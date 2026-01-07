<?php

class TournamentRound extends BaseModel
{
    public function roundsForTournament(int $tournamentId): array
    {
        $sql = "
            SELECT tr.*, m.date_time, s.name AS sport_name
            FROM tournament_rounds tr
            INNER JOIN matches m ON m.id = tr.match_id
            INNER JOIN sports s ON s.id = m.sport_id
            WHERE tr.tournament_id = :tournament_id
            ORDER BY tr.round_number ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tournament_id' => $tournamentId]);

        return $stmt->fetchAll();
    }
}

