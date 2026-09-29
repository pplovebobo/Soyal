<?php

declare(strict_types=1);

final class Database
{
    private PDO $pdo;

    public function __construct(string $databasePath)
    {
        $directory = dirname($databasePath);

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException("Unable to create database directory: {$directory}");
        }

        $this->pdo = new PDO('sqlite:' . $databasePath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->exec('PRAGMA journal_mode=WAL');
        $this->pdo->exec('PRAGMA foreign_keys=ON');

        $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
        if ($schema === false) {
            throw new RuntimeException('Unable to read database schema.');
        }

        $this->pdo->exec($schema);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
