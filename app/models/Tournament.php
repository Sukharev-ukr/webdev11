<?php

class Tournament extends BaseModel
{
    public function all(): array
    {
        $sql = "
            SELECT t.*, s.name AS sport_name
            FROM tournaments t
            INNER JOIN sports s ON s.id = t.sport_id
            ORDER BY t.start_date ASC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function upcoming(): array
    {
        $sql = "
            SELECT t.*, s.name AS sport_name
            FROM tournaments t
            INNER JOIN sports s ON s.id = t.sport_id
            WHERE t.status = 'upcoming'
              OR t.start_date >= CURDATE()
            ORDER BY t.start_date ASC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO tournaments (sport_id, name, description, start_date, end_date, status)
            VALUES (:sport_id, :name, :description, :start_date, :end_date, :status)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':sport_id' => $data['sport_id'],
            ':name' => $data['name'],
            ':description' => $data['description'] ?? null,
            ':start_date' => $data['start_date'] ?? null,
            ':end_date' => $data['end_date'] ?? null,
            ':status' => $data['status'] ?? 'upcoming',
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE tournaments
            SET sport_id = :sport_id,
                name = :name,
                description = :description,
                start_date = :start_date,
                end_date = :end_date,
                status = :status
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':sport_id' => $data['sport_id'],
            ':name' => $data['name'],
            ':description' => $data['description'] ?? null,
            ':start_date' => $data['start_date'] ?? null,
            ':end_date' => $data['end_date'] ?? null,
            ':status' => $data['status'] ?? 'upcoming',
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM tournaments WHERE id = :id");

        return $stmt->execute([':id' => $id]);
    }
}

