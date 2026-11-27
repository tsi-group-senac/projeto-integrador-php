<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/backend/models/User.php';
require_once dirname(__DIR__) . '/backend/models/Destination.php';
require_once dirname(__DIR__) . '/backend/models/Package.php';
require_once dirname(__DIR__) . '/backend/models/Purchase.php';
require_once dirname(__DIR__) . '/backend/services/AuthService.php';
require_once dirname(__DIR__) . '/backend/services/CsrfService.php';
require_once dirname(__DIR__) . '/backend/services/PackageService.php';
require_once dirname(__DIR__) . '/backend/services/PurchaseService.php';
require_once dirname(__DIR__) . '/backend/services/AdminService.php';

function test_assert(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException('FALHA: ' . $message);
    }
}

function test_pass(string $id, string $name): void
{
    fwrite(STDOUT, "[PASS] {$id} — {$name}\n");
}
