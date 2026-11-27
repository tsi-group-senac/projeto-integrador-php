<?php

declare(strict_types=1);

final class Package
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findActiveWithDestinations(): array
    {
        $statement = $this->pdo->query(
            'SELECT p.id, p.title, p.summary, p.description, p.duration_days, p.price,
                    d.id AS destination_id, d.name AS destination_name, d.image_path
             FROM packages p
             INNER JOIN destinations d ON d.id = p.destination_id
             WHERE p.is_active = TRUE AND d.is_active = TRUE
             ORDER BY p.title ASC, p.id ASC'
        );

        return $statement->fetchAll();
    }

    public function findActiveById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT p.id, p.title, p.summary, p.description, p.duration_days, p.price, p.is_active,
                    d.id AS destination_id, d.name AS destination_name,
                    d.description AS destination_description, d.image_path
             FROM packages p
             INNER JOIN destinations d ON d.id = p.destination_id
             WHERE p.id = :id AND p.is_active = TRUE AND d.is_active = TRUE
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $package = $statement->fetch();

        return $package === false ? null : $package;
    }

    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT p.id, p.destination_id, p.title, p.summary, p.description, p.duration_days,
                    p.price, p.is_active, p.created_at, p.updated_at,
                    d.name AS destination_name
             FROM packages p
             INNER JOIN destinations d ON d.id = p.destination_id
             WHERE p.id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $package = $statement->fetch();

        return $package === false ? null : $package;
    }

    public function findAllWithDestinations(): array
    {
        $statement = $this->pdo->query(
            'SELECT p.id, p.destination_id, p.title, p.summary, p.description, p.duration_days,
                    p.price, p.is_active, d.name AS destination_name
             FROM packages p
             INNER JOIN destinations d ON d.id = p.destination_id
             ORDER BY p.is_active DESC, p.title ASC, p.id ASC'
        );

        return $statement->fetchAll();
    }

    public function create(array $data): array
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO packages (
                 destination_id, title, summary, description, duration_days, price, is_active
             ) VALUES (
                 :destination_id, :title, :summary, :description, :duration_days, :price, :is_active
             )'
        );
        $statement->execute([
            'destination_id' => $data['destination_id'],
            'title' => $data['title'],
            'summary' => $data['summary'],
            'description' => $data['description'],
            'duration_days' => $data['duration_days'],
            'price' => $data['price'],
            'is_active' => $data['is_active'] ? 1 : 0,
        ]);

        $created = $this->findById((int) $this->pdo->lastInsertId());

        if ($created === null) {
            throw new RuntimeException('Pacote criado, mas não pôde ser recarregado.');
        }

        return $created;
    }

    public function update(int $id, array $data): array
    {
        $statement = $this->pdo->prepare(
            'UPDATE packages
             SET destination_id = :destination_id,
                 title = :title,
                 summary = :summary,
                 description = :description,
                 duration_days = :duration_days,
                 price = :price,
                 is_active = :is_active
             WHERE id = :id'
        );
        $statement->execute([
            'id' => $id,
            'destination_id' => $data['destination_id'],
            'title' => $data['title'],
            'summary' => $data['summary'],
            'description' => $data['description'],
            'duration_days' => $data['duration_days'],
            'price' => $data['price'],
            'is_active' => $data['is_active'] ? 1 : 0,
        ]);

        $updated = $this->findById($id);

        if ($updated === null) {
            throw new RuntimeException('Pacote atualizado, mas não pôde ser recarregado.');
        }

        return $updated;
    }

    public function deactivate(int $id): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE packages SET is_active = FALSE WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
    }

    public function hardDelete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM packages WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}
