<?php

class Sport extends BaseModel
{
    public function all(): array
    {
        return $this->db->query("SELECT * FROM sports ORDER BY name ASC")->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO sports (name, description) VALUES (:name, :description)");
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'] ?? null,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE sports SET name = :name, description = :description WHERE id = :id");

        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM sports WHERE id = :id");

        return $stmt->execute([':id' => $id]);
    }
}

