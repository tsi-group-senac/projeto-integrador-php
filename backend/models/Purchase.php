<?php

declare(strict_types=1);

final class Purchase
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function create(int $userId, int $packageId): array
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO purchases (user_id, package_id, status)
             VALUES (:user_id, :package_id, :status)'
        );
        $statement->execute([
            'user_id' => $userId,
            'package_id' => $packageId,
            'status' => 'confirmed',
        ]);

        $id = (int) $this->pdo->lastInsertId();
        $created = $this->findById($id);

        if ($created === null) {
            throw new RuntimeException('Aquisição criada, mas não pôde ser recarregada.');
        }

        return $created;
    }

    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, user_id, package_id, status, purchased_at
             FROM purchases
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $purchase = $statement->fetch();

        return $purchase === false ? null : $purchase;
    }

    public function listByUser(int $userId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT pu.id, pu.status, pu.purchased_at, pu.package_id,
                    p.title AS package_title, p.summary AS package_summary,
                    p.duration_days, p.price, p.is_active AS package_is_active,
                    d.name AS destination_name
             FROM purchases pu
             INNER JOIN packages p ON p.id = pu.package_id
             INNER JOIN destinations d ON d.id = p.destination_id
             WHERE pu.user_id = :user_id
             ORDER BY pu.purchased_at DESC, pu.id DESC'
        );
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll();
    }

    public function countByPackage(int $packageId): int
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) FROM purchases WHERE package_id = :package_id'
        );
        $statement->execute(['package_id' => $packageId]);

        return (int) $statement->fetchColumn();
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM purchases')->fetchColumn();
    }

    public function listAllWithDetails(): array
    {
        $statement = $this->pdo->query(
            'SELECT pu.id, pu.status, pu.purchased_at, pu.package_id, pu.user_id,
                    u.name AS customer_name, u.email AS customer_email,
                    p.title AS package_title, p.summary AS package_summary,
                    p.duration_days, p.price, p.is_active AS package_is_active,
                    d.name AS destination_name
             FROM purchases pu
             INNER JOIN users u ON u.id = pu.user_id
             INNER JOIN packages p ON p.id = pu.package_id
             INNER JOIN destinations d ON d.id = p.destination_id
             ORDER BY pu.purchased_at DESC, pu.id DESC'
        );

        return $statement->fetchAll();
    }

    public function listAllWithDetailsPage(int $limit, int $offset): array
    {
        $statement = $this->pdo->prepare(
            'SELECT pu.id, pu.status, pu.purchased_at, pu.package_id, pu.user_id,
                    u.name AS customer_name, u.email AS customer_email,
                    p.title AS package_title, p.summary AS package_summary,
                    p.duration_days, p.price, p.is_active AS package_is_active,
                    d.name AS destination_name
             FROM purchases pu
             INNER JOIN users u ON u.id = pu.user_id
             INNER JOIN packages p ON p.id = pu.package_id
             INNER JOIN destinations d ON d.id = p.destination_id
             ORDER BY pu.purchased_at DESC, pu.id DESC
             LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countByCustomer(int $userId): int
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) FROM purchases WHERE user_id = :user_id'
        );
        $statement->execute(['user_id' => $userId]);

        return (int) $statement->fetchColumn();
    }
}
