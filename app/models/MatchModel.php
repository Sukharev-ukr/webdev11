<?php

class MatchModel extends BaseModel
{
    public function getUpcoming(array $filters = []): array
    {
        // Show matches from today onwards (or past 7 days if none found)
        $conditions = ["m.date_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)"];
        $params = [];

        if (!empty($filters['sport_id'])) {
            $conditions[] = 'm.sport_id = :sport_id';
            $params[':sport_id'] = (int)$filters['sport_id'];
        }

        if (!empty($filters['location_id'])) {
            $conditions[] = 'm.location_id = :location_id';
            $params[':location_id'] = (int)$filters['location_id'];
        }

        if (!empty($filters['date'])) {
            $conditions[] = 'DATE(m.date_time) = :match_date';
            $params[':match_date'] = $filters['date'];
        }

        $sql = "
            SELECT m.*, 
                   COALESCE(s.name, 'Unknown Sport') AS sport_name,
                   COALESCE(l.name, 'Unknown Location') AS location_name,
                   COALESCE(l.city, '') AS location_city,
                   (SELECT COUNT(*) FROM match_participants mp WHERE mp.match_id = m.id) AS participant_count
            FROM matches m
            LEFT JOIN sports s ON s.id = m.sport_id
            LEFT JOIN locations l ON l.id = m.location_id
            WHERE " . implode(' AND ', $conditions) . "
                AND m.status IN ('open', 'full')
            ORDER BY m.date_time ASC
        ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("MatchModel::getUpcoming error: " . $e->getMessage());
            return [];
        }
    }

    public function getAll(): array
    {
        $sql = "
            SELECT m.*, 
                   COALESCE(s.name, 'Unknown Sport') AS sport_name,
                   COALESCE(l.name, 'Unknown Location') AS location_name,
                   COALESCE(l.city, '') AS location_city,
                   (SELECT COUNT(*) FROM match_participants mp WHERE mp.match_id = m.id) AS participant_count
            FROM matches m
            LEFT JOIN sports s ON s.id = m.sport_id
            LEFT JOIN locations l ON l.id = m.location_id
            ORDER BY m.date_time DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function getToday(): array
    {
        $sql = "
            SELECT m.*, s.name AS sport_name, l.name AS location_name, l.city AS location_city
            FROM matches m
            INNER JOIN sports s ON s.id = m.sport_id
            INNER JOIN locations l ON l.id = m.location_id
            WHERE DATE(m.date_time) = CURDATE()
            ORDER BY m.date_time ASC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $sql = "
            SELECT m.*, s.name AS sport_name, l.name AS location_name, l.city AS location_city
            FROM matches m
            INNER JOIN sports s ON s.id = m.sport_id
            INNER JOIN locations l ON l.id = m.location_id
            WHERE m.id = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $match = $stmt->fetch();

        return $match ?: null;
    }

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO matches (sport_id, location_id, creator_id, date_time, max_players, min_skill_level, max_skill_level, status, tournament_id)
            VALUES (:sport_id, :location_id, :creator_id, :date_time, :max_players, :min_skill_level, :max_skill_level, :status, :tournament_id)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':sport_id' => $data['sport_id'],
            ':location_id' => $data['location_id'],
            ':creator_id' => $data['creator_id'],
            ':date_time' => $data['date_time'],
            ':max_players' => $data['max_players'] ?? 10,
            ':min_skill_level' => $data['min_skill_level'] ?? 1,
            ':max_skill_level' => $data['max_skill_level'] ?? 5,
            ':status' => $data['status'] ?? 'open',
            ':tournament_id' => $data['tournament_id'] ?? null,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE matches
            SET sport_id = :sport_id,
                location_id = :location_id,
                date_time = :date_time,
                max_players = :max_players,
                min_skill_level = :min_skill_level,
                max_skill_level = :max_skill_level,
                status = :status,
                tournament_id = :tournament_id
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':sport_id' => $data['sport_id'],
            ':location_id' => $data['location_id'],
            ':date_time' => $data['date_time'],
            ':max_players' => $data['max_players'],
            ':min_skill_level' => $data['min_skill_level'],
            ':max_skill_level' => $data['max_skill_level'],
            ':status' => $data['status'] ?? 'open',
            ':tournament_id' => $data['tournament_id'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM matches WHERE id = :id");

        return $stmt->execute([':id' => $id]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE matches SET status = :status WHERE id = :id");

        return $stmt->execute([
            ':status' => $status,
            ':id' => $id,
        ]);
    }

    public function participantCount(int $matchId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM match_participants WHERE match_id = :match_id");
        $stmt->execute([':match_id' => $matchId]);
        $row = $stmt->fetch();

        return (int)($row['total'] ?? 0);
    }
}

