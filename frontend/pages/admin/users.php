<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../backend/bootstrap.php';
require_once __DIR__ . '/../../../backend/controllers/AdminController.php';

$adminController = new AdminController($adminService, $authService);
$adminController->requireAdmin();

$customers = [];
$pagination = pagination_meta(0);
$loadError = false;

try {
    $result = $adminController->customersPage(null, 10);
    $customers = $result['items'];
    $pagination = $result['meta'];
} catch (PDOException $exception) {
    error_log('Falha ao listar clientes: ' . $exception->getMessage());
    $loadError = true;
}

$activeNav = 'admin';
$pageTitle = __('admin.users_title');
require __DIR__ . '/../../components/head.php';
require __DIR__ . '/../../components/header.php';
?>
<main id="conteudo" class="mx-auto max-w-6xl px-4 py-10">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-dt-ink"><?= e(__('admin.users_heading')) ?></h1>
            <p class="mt-2 text-sm text-dt-muted"><?= e(__('admin.users_sub')) ?></p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a class="inline-flex h-9 items-center rounded-full border border-dt-orange px-4 text-sm font-semibold text-dt-orange hover:bg-[#fff8ee]" href="<?= e(app_url('/frontend/pages/admin/reservations.php')) ?>">
                <?= e(__('admin.reservations')) ?>
            </a>
            <a class="inline-flex h-9 items-center rounded-full border border-[#ddd] px-4 text-sm font-semibold text-dt-ink hover:border-dt-orange hover:text-dt-orange" href="<?= e(app_url('/frontend/pages/admin/index.php')) ?>">
                <?= e(__('admin.back_panel')) ?>
            </a>
        </div>
    </div>

    <?php require __DIR__ . '/../../components/messages.php'; ?>

    <?php if ($loadError): ?>
        <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert"><?= e(__('admin.users_error')) ?></div>
    <?php elseif ($customers === []): ?>
        <div class="rounded-xl border border-[#eee] bg-white px-4 py-6 text-sm text-dt-muted" role="status"><?= e(__('admin.users_empty')) ?></div>
    <?php else: ?>
        <div class="overflow-x-auto rounded-2xl border border-[#eee] bg-white shadow-sm">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-[#eee] bg-[#fafafa]">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-semibold text-dt-ink"><?= e(__('admin.col_name')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold text-dt-ink"><?= e(__('admin.col_email')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold text-dt-ink"><?= e(__('admin.col_created')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold text-dt-ink"><?= e(__('admin.col_purchases')) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr class="border-b border-[#f3f3f3]">
                            <td class="px-4 py-3 font-medium text-dt-ink"><?= e((string) $customer['name']) ?></td>
                            <td class="px-4 py-3 text-dt-muted"><?= e((string) $customer['email']) ?></td>
                            <td class="px-4 py-3 text-dt-muted"><?= e((string) $customer['created_at']) ?></td>
                            <td class="px-4 py-3">
                                <a class="font-semibold text-dt-orange hover:underline" href="<?= e(app_url('/frontend/pages/admin/reservations.php')) ?>">
                                    <?= (int) $customer['purchase_count'] ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php require __DIR__ . '/../../components/pagination.php'; ?>
    <?php endif; ?>
<?php require __DIR__ . '/../../components/footer.php'; ?>
