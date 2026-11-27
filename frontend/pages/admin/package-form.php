<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../backend/bootstrap.php';
require_once __DIR__ . '/../../../backend/controllers/PackageController.php';

$packageController = new PackageController($packageService, $csrfService, $authService);
$packageController->requireAdmin();

$packageId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);
$isEdit = $packageId !== false && $packageId !== null;
$editLookupFailed = false;
$fieldErrors = [];
$formErrors = [];
$destinations = $destinationModel->listActive();

$values = [
    'destination_id' => '',
    'title' => '',
    'summary' => '',
    'description' => '',
    'duration_days' => '',
    'price' => '',
    'is_active' => '1',
];

if ($isEdit) {
    try {
        $existing = $packageService->findById((int) $packageId);
        $values = [
            'destination_id' => (string) $existing['destination_id'],
            'title' => (string) $existing['title'],
            'summary' => (string) $existing['summary'],
            'description' => (string) $existing['description'],
            'duration_days' => (string) $existing['duration_days'],
            'price' => (string) $existing['price'],
            'is_active' => ((int) $existing['is_active'] === 1) ? '1' : '0',
        ];
    } catch (RuntimeException $exception) {
        http_response_code(404);
        $formErrors[] = $exception->getMessage();
        $editLookupFailed = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$editLookupFailed) {
    $values = [
        'destination_id' => (string) ($_POST['destination_id'] ?? ''),
        'title' => trim((string) ($_POST['title'] ?? '')),
        'summary' => trim((string) ($_POST['summary'] ?? '')),
        'description' => trim((string) ($_POST['description'] ?? '')),
        'duration_days' => (string) ($_POST['duration_days'] ?? ''),
        'price' => (string) ($_POST['price'] ?? ''),
        'is_active' => isset($_POST['is_active']) ? '1' : '0',
    ];

    $result = $packageController->save($isEdit ? (int) $packageId : null, array_merge($_POST, [
        'is_active' => $values['is_active'],
    ]));

    if ($result['ok']) {
        flash_set('success', $isEdit ? __('admin.flash_updated') : __('admin.flash_created'));
        redirect(app_url('/frontend/pages/admin/packages.php'));
    }

    if (isset($result['errors'])) {
        $fieldErrors = $result['errors'];
    }
    if (isset($result['message'])) {
        $formErrors[] = $result['message'];
    }
}

$activeNav = 'admin';
$pageTitle = ($isEdit ? __('admin.form_edit') : __('admin.form_new')) . ' | Admin';
require __DIR__ . '/../../components/head.php';
require __DIR__ . '/../../components/header.php';
?>
<main id="conteudo" class="mx-auto max-w-3xl px-4 py-10">
    <h1 class="font-display text-3xl font-bold"><?= e($isEdit ? __('admin.form_edit') : __('admin.form_new')) ?></h1>
    <p class="mt-2 text-brand-900/70"><a class="font-semibold text-brand-700 hover:underline" href="<?= e(app_url('/frontend/pages/admin/packages.php')) ?>"><?= e(__('admin.form_back')) ?></a></p>

    <?php require __DIR__ . '/../../components/messages.php'; ?>

    <form method="post" class="mt-6 space-y-4 rounded-xl border border-brand-100 bg-white p-6 shadow-sm" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($csrfService->token()) ?>">

        <div>
            <label class="mb-1 block text-sm font-semibold" for="destination_id"><?= e(__('admin.form_destination')) ?></label>
            <select class="w-full rounded-md border border-brand-200 px-3 py-2" id="destination_id" name="destination_id" required>
                <option value=""><?= e(__('admin.form_select')) ?></option>
                <?php foreach ($destinations as $destination): ?>
                    <option value="<?= (int) $destination['id'] ?>" <?= $values['destination_id'] === (string) $destination['id'] ? 'selected' : '' ?>>
                        <?= e((string) $destination['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold" for="title"><?= e(__('admin.form_title')) ?></label>
            <input class="w-full rounded-md border border-brand-200 px-3 py-2" id="title" name="title" required maxlength="160" value="<?= e($values['title']) ?>">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold" for="summary"><?= e(__('admin.form_summary')) ?></label>
            <input class="w-full rounded-md border border-brand-200 px-3 py-2" id="summary" name="summary" required maxlength="255" value="<?= e($values['summary']) ?>">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold" for="description"><?= e(__('admin.form_description')) ?></label>
            <textarea class="w-full rounded-md border border-brand-200 px-3 py-2" id="description" name="description" rows="5" required><?= e($values['description']) ?></textarea>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-semibold" for="duration_days"><?= e(__('admin.form_duration')) ?></label>
                <input class="w-full rounded-md border border-brand-200 px-3 py-2" id="duration_days" name="duration_days" type="number" min="1" max="365" required value="<?= e($values['duration_days']) ?>">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold" for="price"><?= e(__('admin.form_price')) ?></label>
                <input class="w-full rounded-md border border-brand-200 px-3 py-2" id="price" name="price" type="number" min="0" step="0.01" required value="<?= e($values['price']) ?>">
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input id="is_active" name="is_active" type="checkbox" value="1" <?= $values['is_active'] === '1' ? 'checked' : '' ?>>
            <label class="text-sm font-semibold" for="is_active"><?= e(__('admin.form_active')) ?></label>
        </div>

        <button class="rounded-md bg-brand-600 px-4 py-2 font-semibold text-white hover:bg-brand-700" type="submit">
            <?= e($isEdit ? __('admin.form_save') : __('admin.form_create')) ?>
        </button>
    </form>
<?php require __DIR__ . '/../../components/footer.php'; ?>
