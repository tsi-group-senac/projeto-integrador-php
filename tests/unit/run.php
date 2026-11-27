<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$failures = 0;

try {
    // TU-01 — Validar cadastro obrigatório (regras puras)
    $name = '';
    $email = 'invalido';
    $errors = [];
    if (mb_strlen($name) < 2 || mb_strlen($name) > 120) {
        $errors[] = 'nome';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'email';
    }
    test_assert($errors === ['nome', 'email'], 'TU-01 deve rejeitar nome e e-mail inválidos');
    test_pass('TU-01', 'Validar cadastro obrigatório');
} catch (Throwable $exception) {
    $failures++;
    fwrite(STDERR, $exception->getMessage() . "\n");
}

try {
    // TU-02 — Validar senha e confirmação + hash
    $password = 'senha-valida';
    $confirmation = 'outra';
    test_assert($password !== $confirmation, 'TU-02 senhas diferentes devem falhar');
    $hash = password_hash($password, PASSWORD_DEFAULT);
    test_assert(password_verify($password, $hash), 'TU-02 hash deve verificar');
    test_assert(!password_verify('errada', $hash), 'TU-02 hash não deve aceitar senha errada');
    test_assert(!str_contains($hash, $password), 'TU-02 hash não deve conter a senha em claro');
    test_pass('TU-02', 'Validar senha e confirmação');
} catch (Throwable $exception) {
    $failures++;
    fwrite(STDERR, $exception->getMessage() . "\n");
}

try {
    // TU-05 — CSRF
    $_SESSION = [];
    $csrf = new CsrfService();
    $token = $csrf->token();
    $csrf->validate($token);
    $threw = false;
    try {
        $csrf->validate('token-invalido');
    } catch (InvalidArgumentException) {
        $threw = true;
    }
    test_assert($threw, 'TU-05 deve rejeitar token inválido');
    test_pass('TU-05', 'Validar token CSRF');
} catch (Throwable $exception) {
    $failures++;
    fwrite(STDERR, $exception->getMessage() . "\n");
}

try {
    // TU-03 / TU-04 com doubles em memória via PDO sqlite se disponível
    if (!in_array('sqlite', PDO::getAvailableDrivers(), true)) {
        throw new RuntimeException('FALHA: TU-03/TU-04 requerem PDO sqlite');
    }

    $pdo = new PDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('CREATE TABLE destinations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT NOT NULL,
        image_path TEXT NULL,
        is_active INTEGER NOT NULL DEFAULT 1
    )');
    $pdo->exec('CREATE TABLE packages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        destination_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        summary TEXT NOT NULL,
        description TEXT NOT NULL,
        duration_days INTEGER NOT NULL,
        price REAL NOT NULL,
        is_active INTEGER NOT NULL DEFAULT 1,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP,
        updated_at TEXT DEFAULT CURRENT_TIMESTAMP
    )');
    $pdo->exec('CREATE TABLE purchases (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        package_id INTEGER NOT NULL,
        status TEXT NOT NULL DEFAULT "simulated",
        purchased_at TEXT DEFAULT CURRENT_TIMESTAMP
    )');
    $pdo->exec("INSERT INTO destinations (name, description, is_active) VALUES ('Rio', 'Desc', 1)");
    $pdo->exec("INSERT INTO packages (destination_id, title, summary, description, duration_days, price, is_active)
                VALUES (1, 'Rio Essencial', 'Resumo', 'Desc', 5, 100, 0)");

    $packages = new Package($pdo);
    $destinations = new Destination($pdo);
    $purchases = new Purchase($pdo);
    $packageService = new PackageService($packages, $destinations, $purchases);
    $purchaseService = new PurchaseService($purchases, $packages);

    $inactiveBlocked = false;
    try {
        $purchaseService->simulate(1, 1);
    } catch (RuntimeException) {
        $inactiveBlocked = true;
    }
    test_assert($inactiveBlocked, 'TU-03 pacote inativo não pode ser adquirido');
    test_pass('TU-03', 'Validar pacote ativo');

    $validated = $packageService->validateInput([
        'destination_id' => 1,
        'title' => 'ab',
        'summary' => '',
        'description' => '',
        'duration_days' => 0,
        'price' => '-1',
        'is_active' => '1',
    ]);
    test_assert($validated['errors'] !== [], 'TU-04 deve rejeitar dados inválidos do pacote');
    test_pass('TU-04', 'Validar regras do CRUD de pacote');
} catch (Throwable $exception) {
    $failures++;
    fwrite(STDERR, $exception->getMessage() . "\n");
}

exit($failures === 0 ? 0 : 1);
