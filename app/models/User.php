<?php

class User extends BaseModel
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO users (name, email, password_hash, role, city, description) 
                VALUES (:name, :email, :password_hash, :role, :city, :description)";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            ':name' => $data['name'],
            ':email' => strtolower($data['email']),
            ':password_hash' => $data['password_hash'],
            ':role' => $data['role'] ?? 'player',
            ':city' => $data['city'] ?? null,
            ':description' => $data['description'] ?? null,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => strtolower($email)]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function updateProfile(int $userId, array $data): bool
    {
        $sql = "UPDATE users 
                SET name = :name, city = :city, description = :description 
                WHERE id = :id";
        $statement = $this->db->prepare($sql);

        return $statement->execute([
            ':name' => $data['name'],
            ':city' => $data['city'] ?? null,
            ':description' => $data['description'] ?? null,
            ':id' => $userId,
        ]);
    }

    public function updatePassword(int $userId, string $passwordHash): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");

        return $stmt->execute([
            ':hash' => $passwordHash,
            ':id' => $userId,
        ]);
    }

    public function setSports(int $userId, array $sports): void
    {
        $this->db->beginTransaction();
        $this->db->prepare("DELETE FROM user_sports WHERE user_id = :id")->execute([':id' => $userId]);

        $sql = "INSERT INTO user_sports (user_id, sport_id, skill_level, preferred_position) 
                VALUES (:user_id, :sport_id, :skill_level, :preferred_position)";
        $stmt = $this->db->prepare($sql);

        foreach ($sports as $sport) {
            if (empty($sport['sport_id']) || empty($sport['skill_level'])) {
                continue;
            }

            $skill = max(1, min(5, (int)$sport['skill_level']));
            $stmt->execute([
                ':user_id' => $userId,
                ':sport_id' => (int)$sport['sport_id'],
                ':skill_level' => $skill,
                ':preferred_position' => $sport['preferred_position'] ?? null,
            ]);
        }

        $this->db->commit();
    }

    public function getSports(int $userId): array
    {
        $sql = "SELECT us.*, s.name AS sport_name 
                FROM user_sports us
                INNER JOIN sports s ON s.id = us.sport_id
                WHERE us.user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $userId]);

        return $stmt->fetchAll();
    }

    public function getSkillForSport(int $userId, int $sportId): ?int
    {
        $stmt = $this->db->prepare("SELECT skill_level FROM user_sports WHERE user_id = :user_id AND sport_id = :sport_id");
        $stmt->execute([
            ':user_id' => $userId,
            ':sport_id' => $sportId,
        ]);
        $row = $stmt->fetch();

        return $row ? (int)$row['skill_level'] : null;
    }

    public function getMatchHistory(int $userId): array
    {
        $sql = "SELECT m.*, s.name AS sport_name, l.name AS location_name, mp.result
                FROM match_participants mp
                INNER JOIN matches m ON m.id = mp.match_id
                INNER JOIN sports s ON s.id = m.sport_id
                INNER JOIN locations l ON l.id = m.location_id
                WHERE mp.user_id = :user_id
                ORDER BY m.date_time DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll();
    }
}

