<?php

declare(strict_types=1);

final class User
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, name, email, password_hash, role
             FROM users
             WHERE email = :email
             LIMIT 1'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        return $user === false ? null : $user;
    }

    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, name, email, role, created_at
             FROM users
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $user = $statement->fetch();

        return $user === false ? null : $user;
    }

    public function createCustomer(string $name, string $email, string $password): array
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO users (name, email, password_hash, role)
             VALUES (:name, :email, :password_hash, :role)'
        );
        $statement->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'customer',
        ]);

        return [
            'id' => (int) $this->pdo->lastInsertId(),
            'name' => $name,
            'email' => $email,
            'role' => 'customer',
        ];
    }

    public function countCustomers(): int
    {
        return (int) $this->pdo->query(
            "SELECT COUNT(*) FROM users WHERE role = 'customer'"
        )->fetchColumn();
    }

    public function listCustomersWithPurchaseCounts(): array
    {
        $statement = $this->pdo->query(
            "SELECT u.id, u.name, u.email, u.created_at,
                    COUNT(p.id) AS purchase_count
             FROM users u
             LEFT JOIN purchases p ON p.user_id = u.id
             WHERE u.role = 'customer'
             GROUP BY u.id, u.name, u.email, u.created_at
             ORDER BY u.name ASC, u.id ASC"
        );

        return $statement->fetchAll();
    }

    public function listCustomersWithPurchaseCountsPage(int $limit, int $offset): array
    {
        $statement = $this->pdo->prepare(
            "SELECT u.id, u.name, u.email, u.created_at,
                    COUNT(p.id) AS purchase_count
             FROM users u
             LEFT JOIN purchases p ON p.user_id = u.id
             WHERE u.role = 'customer'
             GROUP BY u.id, u.name, u.email, u.created_at
             ORDER BY u.name ASC, u.id ASC
             LIMIT :limit OFFSET :offset"
        );
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
