<?php

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $settings = [
                'host' => getenv('DB_HOST') ?: '127.0.0.1',
                'port' => getenv('DB_PORT') ?: '3306',
                'name' => getenv('DB_NAME') ?: 'squadsport',
                'user' => getenv('DB_USER') ?: 'root',
                'pass' => getenv('DB_PASSWORD') ?: '',
                'charset' => 'utf8mb4',
            ];

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $settings['host'],
                $settings['port'],
                $settings['name'],
                $settings['charset']
            );

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            self::$connection = new PDO($dsn, $settings['user'], $settings['pass'], $options);
            self::ensureProfileDescriptionColumn();
            self::ensureEventsTable();
        }

        return self::$connection;
    }

    private static function ensureProfileDescriptionColumn(): void
    {
        $stmt = self::$connection->prepare("
            SELECT COUNT(*) AS total 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'users'
              AND COLUMN_NAME = 'description'
        ");
        $stmt->execute();
        $result = $stmt->fetch();

        if ((int)($result['total'] ?? 0) === 0) {
            self::$connection->exec("ALTER TABLE users ADD COLUMN description TEXT NULL AFTER city");
        }
    }

    private static function ensureEventsTable(): void
    {
        $stmt = self::$connection->prepare("
            SELECT COUNT(*) AS total
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'events'
        ");
        $stmt->execute();
        $exists = (int)($stmt->fetch()['total'] ?? 0) > 0;

        if (!$exists) {
            self::$connection->exec("
                CREATE TABLE events (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(150) NOT NULL,
                    description TEXT,
                    start_at DATETIME NOT NULL,
                    venue VARCHAR(150),
                    city VARCHAR(100),
                    link VARCHAR(255),
                    created_by INT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }
    }
}

