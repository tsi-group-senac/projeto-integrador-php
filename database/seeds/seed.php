<?php

declare(strict_types=1);

/**
 * Loads demonstrative data using passwords supplied only through the environment.
 *
 * Example:
 * DB_HOST=127.0.0.1 DB_PORT=3306 DB_NAME=projeto_integrador \
 * DB_USER=root DB_PASSWORD="$DB_PASSWORD" \
 * SEED_ADMIN_PASSWORD="$SEED_ADMIN_PASSWORD" \
 * SEED_CUSTOMER_PASSWORD="$SEED_CUSTOMER_PASSWORD" php database/seeds/seed.php
 */

$requiredEnvironment = [
    'SEED_ADMIN_PASSWORD',
    'SEED_CUSTOMER_PASSWORD',
];

foreach ($requiredEnvironment as $variable) {
    if (getenv($variable) === false || getenv($variable) === '') {
        fwrite(STDERR, "A variável {$variable} deve ser definida.\n");
        exit(1);
    }
}

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_NAME') ?: 'projeto_integrador';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

$dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

/** @var array<string, array<string, mixed>> $catalog */
$catalog = require dirname(__DIR__, 2) . '/backend/data/destinations-catalog.php';

$admins = [
    ['name' => 'Thiago Barbosa', 'email' => 'thiago.barbosa@destyntrips.com'],
    ['name' => 'Arthur Inacio', 'email' => 'arthur.inacio@destyntrips.com'],
    ['name' => 'Pedro Gomes', 'email' => 'pedro.gomes@destyntrips.com'],
];

$customers = [
    ['name' => 'Marina Costa', 'email' => 'marina.costa@destyntrips.com'],
    ['name' => 'Lucas Almeida', 'email' => 'lucas.almeida@email.com'],
    ['name' => 'Fernanda Ribeiro', 'email' => 'fernanda.ribeiro@email.com'],
    ['name' => 'Bruno Oliveira', 'email' => 'bruno.oliveira@email.com'],
    ['name' => 'Camila Santos', 'email' => 'camila.santos@email.com'],
    ['name' => 'Rafael Mendes', 'email' => 'rafael.mendes@email.com'],
    ['name' => 'Juliana Ferreira', 'email' => 'juliana.ferreira@email.com'],
    ['name' => 'Gabriel Souza', 'email' => 'gabriel.souza@email.com'],
    ['name' => 'Beatriz Lima', 'email' => 'beatriz.lima@email.com'],
    ['name' => 'André Carvalho', 'email' => 'andre.carvalho@email.com'],
    ['name' => 'Patrícia Nunes', 'email' => 'patricia.nunes@email.com'],
    ['name' => 'Diego Martins', 'email' => 'diego.martins@email.com'],
    ['name' => 'Larissa Rocha', 'email' => 'larissa.rocha@email.com'],
    ['name' => 'Felipe Araujo', 'email' => 'felipe.araujo@email.com'],
    ['name' => 'Amanda Vieira', 'email' => 'amanda.vieira@email.com'],
    ['name' => 'Rodrigo Barbosa', 'email' => 'rodrigo.barbosa@email.com'],
    ['name' => 'Isabela Monteiro', 'email' => 'isabela.monteiro@email.com'],
    ['name' => 'Thiago Freitas', 'email' => 'thiago.freitas@email.com'],
    ['name' => 'Natália Campos', 'email' => 'natalia.campos@email.com'],
    ['name' => 'Henrique Duarte', 'email' => 'henrique.duarte@email.com'],
];

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $pdo->beginTransaction();

    $userStatement = $pdo->prepare(
        'INSERT INTO users (name, email, password_hash, role)
         VALUES (:name, :email, :password_hash, :role)
         ON DUPLICATE KEY UPDATE
             name = VALUES(name),
             password_hash = VALUES(password_hash),
             role = VALUES(role)'
    );

    $adminHash = password_hash((string) getenv('SEED_ADMIN_PASSWORD'), PASSWORD_DEFAULT);
    foreach ($admins as $admin) {
        $userStatement->execute([
            'name' => $admin['name'],
            'email' => $admin['email'],
            'password_hash' => $adminHash,
            'role' => 'admin',
        ]);
    }

    $customerHash = password_hash((string) getenv('SEED_CUSTOMER_PASSWORD'), PASSWORD_DEFAULT);
    foreach ($customers as $customer) {
        $userStatement->execute([
            'name' => $customer['name'],
            'email' => $customer['email'],
            'password_hash' => $customerHash,
            'role' => 'customer',
        ]);
    }

    // Remove contas antigas de demo que não fazem mais parte do seed.
    $pdo->exec(
        "DELETE FROM users
         WHERE email IN (
             'admin@destyntrips.com',
             'admin@agencia.test',
             'cliente@agencia.test'
         )"
    );

    $findDestinationStatement = $pdo->prepare(
        'SELECT id FROM destinations WHERE name = :name LIMIT 1'
    );
    $destinationStatement = $pdo->prepare(
        'INSERT INTO destinations (name, description, image_path, is_active)
         VALUES (:name, :description, :image_path, TRUE)'
    );
    $updateDestinationStatement = $pdo->prepare(
        'UPDATE destinations
         SET description = :description, image_path = :image_path, is_active = TRUE
         WHERE id = :id'
    );

    $findPackageStatement = $pdo->prepare(
        'SELECT id FROM packages
         WHERE destination_id = :destination_id AND title = :title
         LIMIT 1'
    );
    $packageStatement = $pdo->prepare(
        'INSERT INTO packages (
             destination_id, title, summary, description, duration_days, price, is_active
         ) VALUES (
             :destination_id, :title, :summary, :description, :duration_days, :price, TRUE
         )'
    );

    $packageTitles = [
        'dubai' => 'Dubai Skyline',
        'bali' => 'Bali Cultural',
        'sydney' => 'Sydney Harbour',
        'phuket' => 'Phuket Islands',
        'shanghai' => 'Shanghai Lights',
        'london' => 'London Classics',
        'paris' => 'Paris Romance',
        'maldives' => 'Maldives Escape',
        'rio-de-janeiro' => 'Rio Essencial',
        'foz-do-iguacu' => 'Cataratas em Família',
    ];

    $destinationIds = [];

    foreach ($catalog as $slug => $item) {
        $name = (string) $item['city'];
        $description = (string) $item['summary'];
        $imagePath = (string) $item['image'];

        $findDestinationStatement->execute(['name' => $name]);
        $destinationId = $findDestinationStatement->fetchColumn();

        if ($destinationId === false) {
            $destinationStatement->execute([
                'name' => $name,
                'description' => $description,
                'image_path' => $imagePath,
            ]);
            $destinationId = (int) $pdo->lastInsertId();
        } else {
            $destinationId = (int) $destinationId;
            $updateDestinationStatement->execute([
                'id' => $destinationId,
                'description' => $description,
                'image_path' => $imagePath,
            ]);
        }

        $destinationIds[] = $destinationId;

        $title = $packageTitles[$slug] ?? ($name . ' Trip');
        $findPackageStatement->execute([
            'destination_id' => $destinationId,
            'title' => $title,
        ]);

        $existingPackageId = $findPackageStatement->fetchColumn();
        $packagePayload = [
            'destination_id' => $destinationId,
            'title' => $title,
            'summary' => (string) $item['summary'],
            'description' => (string) $item['description'],
            'duration_days' => (int) $item['duration_days'],
            'price' => number_format((float) $item['price'], 2, '.', ''),
        ];

        if ($existingPackageId === false) {
            $packageStatement->execute($packagePayload);
        } else {
            $updatePackageStatement = $pdo->prepare(
                'UPDATE packages
                 SET summary = :summary,
                     description = :description,
                     duration_days = :duration_days,
                     price = :price,
                     is_active = TRUE
                 WHERE id = :id'
            );
            $updatePackageStatement->execute([
                'id' => (int) $existingPackageId,
                'summary' => $packagePayload['summary'],
                'description' => $packagePayload['description'],
                'duration_days' => $packagePayload['duration_days'],
                'price' => $packagePayload['price'],
            ]);
        }
    }

    $packageIds = $pdo->query('SELECT id FROM packages WHERE is_active = TRUE ORDER BY id ASC')->fetchAll(PDO::FETCH_COLUMN);
    $customerIds = $pdo->query("SELECT id FROM users WHERE role = 'customer' ORDER BY id ASC")->fetchAll(PDO::FETCH_COLUMN);

    $findPurchaseStatement = $pdo->prepare(
        'SELECT id FROM purchases WHERE user_id = :user_id AND package_id = :package_id LIMIT 1'
    );
    $purchaseStatement = $pdo->prepare(
        'INSERT INTO purchases (user_id, package_id, status)
         VALUES (:user_id, :package_id, :status)'
    );

    // Garante volume suficiente para paginação de reservas.
    if (is_array($packageIds) && $packageIds !== [] && is_array($customerIds)) {
        $pairs = 0;
        foreach ($customerIds as $index => $customerId) {
            $pkgA = (int) $packageIds[$index % count($packageIds)];
            $pkgB = (int) $packageIds[($index + 3) % count($packageIds)];

            foreach ([$pkgA, $pkgB] as $packageId) {
                $findPurchaseStatement->execute([
                    'user_id' => (int) $customerId,
                    'package_id' => $packageId,
                ]);
                if ($findPurchaseStatement->fetchColumn() === false) {
                    $purchaseStatement->execute([
                        'user_id' => (int) $customerId,
                        'package_id' => $packageId,
                        'status' => 'confirmed',
                    ]);
                    $pairs++;
                }
            }
        }
    }

    $pdo->commit();
    fwrite(STDOUT, "Seeds carregadas com sucesso.\n");
} catch (Throwable $exception) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    fwrite(STDERR, "Falha ao carregar seeds: {$exception->getMessage()}\n");
    exit(1);
}
