<?php

class Event extends BaseModel
{
    public function all(): array
    {
        $sql = "
            SELECT e.*, u.name AS creator_name
            FROM events e
            LEFT JOIN users u ON u.id = e.created_by
            ORDER BY e.start_at DESC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function upcoming(int $limit = 4): array
    {
        $sql = "
            SELECT * FROM events
            WHERE start_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            ORDER BY start_at ASC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create(array $data, int $userId): int
    {
        $sql = "
            INSERT INTO events (title, description, start_at, venue, city, link, created_by)
            VALUES (:title, :description, :start_at, :venue, :city, :link, :created_by)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null,
            ':start_at' => $data['start_at'],
            ':venue' => $data['venue'] ?? null,
            ':city' => $data['city'] ?? null,
            ':link' => $data['link'] ?? null,
            ':created_by' => $userId,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $eventId, array $data): bool
    {
        $sql = "
            UPDATE events
            SET title = :title,
                description = :description,
                start_at = :start_at,
                venue = :venue,
                city = :city,
                link = :link
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null,
            ':start_at' => $data['start_at'],
            ':venue' => $data['venue'] ?? null,
            ':city' => $data['city'] ?? null,
            ':link' => $data['link'] ?? null,
            ':id' => $eventId,
        ]);
    }

    public function delete(int $eventId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = :id");
        return $stmt->execute([':id' => $eventId]);
    }
}

