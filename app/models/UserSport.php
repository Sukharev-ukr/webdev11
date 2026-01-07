<?php

class UserSport extends BaseModel
{
    public function forUser(int $userId): array
    {
        $sql = "SELECT * FROM user_sports WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll();
    }
}

