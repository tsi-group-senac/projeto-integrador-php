<?php

declare(strict_types=1);

require_once __DIR__ . '/../../backend/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido.');
}

try {
    $csrfService->validate(isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : null);
    $authService->logout();
} catch (InvalidArgumentException $exception) {
    http_response_code(422);
    exit($exception->getMessage());
}

redirect(app_url('/frontend/pages/login.php?logged_out=1'));
