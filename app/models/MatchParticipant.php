<?php

class MatchParticipant extends BaseModel
{
    public function userIsInMatch(int $matchId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM match_participants WHERE match_id = :match_id AND user_id = :user_id");
        $stmt->execute([
            ':match_id' => $matchId,
            ':user_id' => $userId,
        ]);

        return (bool)$stmt->fetch();
    }

    public function addParticipant(int $matchId, int $userId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO match_participants (match_id, user_id) VALUES (:match_id, :user_id)");

        return $stmt->execute([
            ':match_id' => $matchId,
            ':user_id' => $userId,
        ]);
    }

    public function removeParticipant(int $matchId, int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM match_participants WHERE match_id = :match_id AND user_id = :user_id");

        return $stmt->execute([
            ':match_id' => $matchId,
            ':user_id' => $userId,
        ]);
    }

    public function updateResult(int $participantId, string $result): bool
    {
        $stmt = $this->db->prepare("UPDATE match_participants SET result = :result WHERE id = :id");

        return $stmt->execute([
            ':result' => $result,
            ':id' => $participantId,
        ]);
    }

    public function participantsForMatch(int $matchId): array
    {
        $sql = "SELECT mp.*, u.name 
                FROM match_participants mp
                INNER JOIN users u ON u.id = mp.user_id
                WHERE mp.match_id = :match_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':match_id' => $matchId]);

        return $stmt->fetchAll();
    }
}

