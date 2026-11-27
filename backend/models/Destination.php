<?php

declare(strict_types=1);

final class Destination
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findActiveById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, name, description, image_path
             FROM destinations
             WHERE id = :id AND is_active = TRUE
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $destination = $statement->fetch();

        return $destination === false ? null : $destination;
    }

    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, name, description, image_path, is_active
             FROM destinations
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $destination = $statement->fetch();

        return $destination === false ? null : $destination;
    }

    public function listActive(): array
    {
        $statement = $this->pdo->query(
            'SELECT id, name
             FROM destinations
             WHERE is_active = TRUE
             ORDER BY name ASC, id ASC'
        );

        return $statement->fetchAll();
    }
}
