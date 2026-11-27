<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../backend/bootstrap.php';
require_once __DIR__ . '/../../../backend/controllers/PackageController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(__('admin.method_not_allowed'));
}

$packageController = new PackageController($packageService, $csrfService, $authService);
$packageId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

if ($packageId === false || $packageId === null) {
    flash_set('error', __('admin.flash_invalid'));
    redirect(app_url('/frontend/pages/admin/packages.php'));
}

$result = $packageController->delete((int) $packageId, $_POST);

if ($result['ok']) {
    flash_set('success', __('admin.flash_deleted'));
} else {
    flash_set('error', $result['message'] ?? __('admin.flash_fail'));
}

redirect(app_url('/frontend/pages/admin/packages.php'));
