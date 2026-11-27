<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $isHttps,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/i18n/I18n.php';
require_once __DIR__ . '/helpers/pagination.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Destination.php';
require_once __DIR__ . '/models/Package.php';
require_once __DIR__ . '/models/Purchase.php';
require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/CsrfService.php';
require_once __DIR__ . '/services/PackageService.php';
require_once __DIR__ . '/services/PurchaseService.php';
require_once __DIR__ . '/services/AdminService.php';

I18n::boot();

try {
    $pdo = require __DIR__ . '/config/connection.php';
} catch (RuntimeException $exception) {
    http_response_code(500);
    exit(__('db.unavailable'));
}

$userModel = new User($pdo);
$destinationModel = new Destination($pdo);
$packageModel = new Package($pdo);
$purchaseModel = new Purchase($pdo);
$authService = new AuthService($userModel);
$csrfService = new CsrfService();
$packageService = new PackageService($packageModel, $destinationModel, $purchaseModel);
$purchaseService = new PurchaseService($purchaseModel, $packageModel);
$adminService = new AdminService($userModel, $purchaseModel);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * @param array<string, string|int|float> $replace
 */
function __(string $key, array $replace = []): string
{
    return I18n::get($key, $replace);
}

function locale(): string
{
    return I18n::locale();
}

function redirect(string $location): never
{
    header('Location: ' . $location, true, 303);
    exit;
}

function project_base_path(): string
{
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));

    if (preg_match('#^(.*?)/frontend(?:/|$)#', $script, $matches) === 1) {
        return rtrim($matches[1], '/');
    }

    return '';
}

function app_url(string $path): string
{
    $base = project_base_path();
    $normalized = '/' . ltrim($path, '/');

    return $base . $normalized;
}

function current_user(): ?array
{
    $user = $_SESSION['user'] ?? null;

    return is_array($user) ? $user : null;
}

function flash_set(string $type, string $message): void
{
    $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    if (!isset($_SESSION['_flash']) || !is_array($_SESSION['_flash'])) {
        return null;
    }

    $flash = $_SESSION['_flash'];
    unset($_SESSION['_flash']);

    return $flash;
}
