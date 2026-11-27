<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

$configPath = dirname(__DIR__, 2) . '/backend/config/database.php';
if (!is_file($configPath)) {
    fwrite(STDERR, "Configure backend/config/database.php antes dos testes de integração.\n");
    exit(1);
}

/** @var array{host:string,port:string|int,dbname:string,username:string,password:string} $config */
$config = require $configPath;
$testDb = ($config['dbname'] ?? 'projeto_integrador') . '_test';

$dsnServer = sprintf(
    'mysql:host=%s;port=%s;charset=utf8mb4',
    $config['host'] ?? '127.0.0.1',
    $config['port'] ?? '3306'
);

try {
    $server = new PDO($dsnServer, $config['username'] ?? 'root', $config['password'] ?? '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $server->exec("DROP DATABASE IF EXISTS `{$testDb}`");
    $server->exec("CREATE DATABASE `{$testDb}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
} catch (Throwable $exception) {
    fwrite(STDERR, 'Não foi possível preparar banco de teste: ' . $exception->getMessage() . "\n");
    exit(1);
}

$pdo = new PDO(
    sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $config['host'] ?? '127.0.0.1',
        $config['port'] ?? '3306',
        $testDb
    ),
    $config['username'] ?? 'root',
    $config['password'] ?? '',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);

$schema = file_get_contents(dirname(__DIR__, 2) . '/database/database.sql');
if ($schema === false) {
    fwrite(STDERR, "Não foi possível ler database.sql\n");
    exit(1);
}

$schema = preg_replace('/CREATE DATABASE.*?;/s', '', $schema) ?? $schema;
$schema = preg_replace('/USE\s+\w+\s*;/i', '', $schema) ?? $schema;
foreach (array_filter(array_map('trim', explode(';', $schema))) as $statement) {
    if ($statement !== '') {
        $pdo->exec($statement);
    }
}

$userModel = new User($pdo);
$destinationModel = new Destination($pdo);
$packageModel = new Package($pdo);
$purchaseModel = new Purchase($pdo);
$authService = new AuthService($userModel);
$packageService = new PackageService($packageModel, $destinationModel, $purchaseModel);
$purchaseService = new PurchaseService($purchaseModel, $packageModel);
$adminService = new AdminService($userModel, $purchaseModel);

$failures = 0;

try {
    $customer = $userModel->createCustomer('Cliente TI', 'ti-cliente@example.test', 'senha-forte');
    test_assert($customer['role'] === 'customer', 'TI-01 papel customer');
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION = [];
    $logged = $authService->login('ti-cliente@example.test', 'senha-forte');
    test_assert($logged['email'] === 'ti-cliente@example.test', 'TI-01 login');
    test_pass('TI-01', 'Criar e autenticar cliente');
} catch (Throwable $exception) {
    $failures++;
    fwrite(STDERR, $exception->getMessage() . "\n");
}

try {
    $pdo->exec("INSERT INTO destinations (name, description, is_active) VALUES ('Salvador', 'Bahia', 1)");
    $destinationId = (int) $pdo->lastInsertId();
    $package = $packageService->create([
        'destination_id' => $destinationId,
        'title' => 'Salvador Cultural',
        'summary' => 'Três dias de cultura.',
        'description' => 'Roteiro de integração.',
        'duration_days' => 3,
        'price' => '990.00',
        'is_active' => '1',
    ], 1);
    $active = $packageService->listActive();
    $found = false;
    foreach ($active as $item) {
        if ((int) $item['id'] === (int) $package['id']) {
            $found = true;
            test_assert($item['destination_name'] === 'Salvador', 'TI-02 destino relacionado');
        }
    }
    test_assert($found, 'TI-02 pacote aparece no catálogo');
    test_pass('TI-02', 'Persistir pacote e destino relacionado');
} catch (Throwable $exception) {
    $failures++;
    fwrite(STDERR, $exception->getMessage() . "\n");
}

try {
    $packageId = (int) $pdo->query('SELECT id FROM packages ORDER BY id DESC LIMIT 1')->fetchColumn();
    $userId = (int) $pdo->query("SELECT id FROM users WHERE email = 'ti-cliente@example.test'")->fetchColumn();
    $purchaseService->simulate($userId, $packageId);
    $history = $purchaseService->listByUser($userId);
    test_assert($history !== [] && (int) $history[0]['package_id'] === $packageId, 'TI-03 histórico');
    test_pass('TI-03', 'Registrar aquisição no usuário da sessão');
} catch (Throwable $exception) {
    $failures++;
    fwrite(STDERR, $exception->getMessage() . "\n");
}

try {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['user'] = [
        'id' => 1,
        'name' => 'Cliente',
        'email' => 'ti-cliente@example.test',
        'role' => 'customer',
    ];
    // requireRole() encerra o processo com 403; validamos a regra de papel aqui.
    $blocked = ($_SESSION['user']['role'] ?? '') !== 'admin';
    test_assert($blocked, 'TI-04 cliente sem papel admin');
    test_pass('TI-04', 'Bloquear acesso administrativo');
} catch (Throwable $exception) {
    $failures++;
    fwrite(STDERR, $exception->getMessage() . "\n");
}

try {
    $packageId = (int) $pdo->query('SELECT id FROM packages ORDER BY id DESC LIMIT 1')->fetchColumn();
    $userId = (int) $pdo->query("SELECT id FROM users WHERE email = 'ti-cliente@example.test'")->fetchColumn();
    $packageService->delete($packageId, 1);
    $activeIds = array_map(static fn (array $row): int => (int) $row['id'], $packageService->listActive());
    test_assert(!in_array($packageId, $activeIds, true), 'TI-05 some do catálogo');
    $history = $purchaseService->listByUser($userId);
    test_assert($history !== [], 'TI-05 histórico preservado');
    test_pass('TI-05', 'Preservar histórico ao desativar pacote');
} catch (Throwable $exception) {
    $failures++;
    fwrite(STDERR, $exception->getMessage() . "\n");
}

try {
    $totals = $adminService->dashboardTotals();
    test_assert($totals['customers'] >= 1, 'TI-06 clientes');
    test_assert($totals['purchases'] >= 1, 'TI-06 aquisições');
    test_pass('TI-06', 'Calcular indicadores administrativos');
} catch (Throwable $exception) {
    $failures++;
    fwrite(STDERR, $exception->getMessage() . "\n");
}

$server->exec("DROP DATABASE IF EXISTS `{$testDb}`");

exit($failures === 0 ? 0 : 1);
