<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../backend/bootstrap.php';
require_once __DIR__ . '/../../../backend/controllers/PackageController.php';

$packageController = new PackageController($packageService, $csrfService, $authService);
$packageController->requireAdmin();

$packages = [];
$loadError = false;

try {
    $packages = $packageService->listAll();
} catch (PDOException $exception) {
    error_log('Falha ao listar pacotes admin: ' . $exception->getMessage());
    $loadError = true;
}

$activeNav = 'admin';
$pageTitle = __('admin.packages_title');
require __DIR__ . '/../../components/head.php';
require __DIR__ . '/../../components/header.php';
?>
<main id="conteudo" class="mx-auto max-w-6xl px-4 py-10">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl font-bold"><?= e(__('admin.packages_heading')) ?></h1>
            <p class="mt-2 text-brand-900/70"><?= e(__('admin.packages_sub')) ?></p>
        </div>
        <a class="rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white" href="<?= e(app_url('/frontend/pages/admin/package-form.php')) ?>"><?= e(__('admin.new_package')) ?></a>
    </div>

    <?php require __DIR__ . '/../../components/messages.php'; ?>

    <?php if ($loadError): ?>
        <div class="rounded-md border border-red-300 bg-red-50 px-4 py-3 text-red-900" role="alert"><?= e(__('admin.packages_error')) ?></div>
    <?php elseif ($packages === []): ?>
        <div class="rounded-md border border-brand-200 bg-white px-4 py-3" role="status"><?= e(__('admin.packages_empty')) ?></div>
    <?php else: ?>
        <div class="overflow-x-auto rounded-xl border border-brand-100 bg-white shadow-sm">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-brand-100 bg-brand-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-semibold"><?= e(__('admin.col_title')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold"><?= e(__('admin.col_destination')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold"><?= e(__('admin.col_price')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold"><?= e(__('admin.col_status')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold"><?= e(__('admin.col_actions')) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($packages as $package): ?>
                        <tr class="border-b border-brand-50">
                            <td class="px-4 py-3"><?= e((string) $package['title']) ?></td>
                            <td class="px-4 py-3"><?= e((string) $package['destination_name']) ?></td>
                            <td class="px-4 py-3">R$ <?= e(number_format((float) $package['price'], 2, ',', '.')) ?></td>
                            <td class="px-4 py-3"><?= ((int) $package['is_active'] === 1) ? e(__('admin.active')) : e(__('admin.inactive')) ?></td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a class="font-semibold text-brand-700 hover:underline" href="<?= e(app_url('/frontend/pages/admin/package-form.php?id=' . (int) $package['id'])) ?>"><?= e(__('admin.edit')) ?></a>
                                    <?php if ((int) $package['is_active'] === 1): ?>
                                        <form method="post" action="<?= e(app_url('/frontend/pages/admin/package-delete.php')) ?>" onsubmit="return confirm(<?= json_encode(__('admin.deactivate_confirm'), JSON_UNESCAPED_UNICODE) ?>);">
                                            <input type="hidden" name="csrf_token" value="<?= e($csrfService->token()) ?>">
                                            <input type="hidden" name="id" value="<?= (int) $package['id'] ?>">
                                            <button class="font-semibold text-red-700 hover:underline" type="submit"><?= e(__('admin.deactivate')) ?></button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php require __DIR__ . '/../../components/footer.php'; ?>
