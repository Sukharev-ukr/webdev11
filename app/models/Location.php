<?php

class Location extends BaseModel
{
    public function all(): array
    {
        return $this->db->query("SELECT * FROM locations ORDER BY city, name")->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO locations (name, address, city) VALUES (:name, :address, :city)");
        $stmt->execute([
            ':name' => $data['name'],
            ':address' => $data['address'] ?? null,
            ':city' => $data['city'] ?? null,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE locations 
            SET name = :name, address = :address, city = :city 
            WHERE id = :id
        ");

        return $stmt->execute([
            ':name' => $data['name'],
            ':address' => $data['address'] ?? null,
            ':city' => $data['city'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM locations WHERE id = :id");

        return $stmt->execute([':id' => $id]);
    }
}

