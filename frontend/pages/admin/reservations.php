<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../backend/bootstrap.php';
require_once __DIR__ . '/../../../backend/controllers/AdminController.php';

$adminController = new AdminController($adminService, $authService);
$adminController->requireAdmin();

$reservations = [];
$pagination = pagination_meta(0);
$loadError = false;

try {
    $result = $adminController->reservationsPage(null, 10);
    $reservations = $result['items'];
    $pagination = $result['meta'];
} catch (PDOException $exception) {
    error_log('Falha ao listar reservas admin: ' . $exception->getMessage());
    $loadError = true;
}

$activeNav = 'admin';
$pageTitle = __('admin.reservations_title');
require __DIR__ . '/../../components/head.php';
require __DIR__ . '/../../components/header.php';
?>
<main id="conteudo" class="mx-auto max-w-6xl px-4 py-10">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-dt-ink"><?= e(__('admin.reservations_heading')) ?></h1>
            <p class="mt-2 text-sm text-dt-muted"><?= e(__('admin.reservations_sub')) ?></p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a class="inline-flex h-9 items-center rounded-full border border-dt-orange px-4 text-sm font-semibold text-dt-orange hover:bg-[#fff8ee]" href="<?= e(app_url('/frontend/pages/admin/users.php')) ?>">
                <?= e(__('admin.customers')) ?>
            </a>
            <a class="inline-flex h-9 items-center rounded-full border border-[#ddd] px-4 text-sm font-semibold text-dt-ink hover:border-dt-orange hover:text-dt-orange" href="<?= e(app_url('/frontend/pages/admin/index.php')) ?>">
                <?= e(__('admin.back_panel')) ?>
            </a>
        </div>
    </div>

    <?php require __DIR__ . '/../../components/messages.php'; ?>

    <?php if ($loadError): ?>
        <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert"><?= e(__('admin.reservations_error')) ?></div>
    <?php elseif ($reservations === []): ?>
        <div class="rounded-xl border border-[#eee] bg-white px-4 py-6 text-sm text-dt-muted" role="status"><?= e(__('admin.reservations_empty')) ?></div>
    <?php else: ?>
        <div class="overflow-x-auto rounded-2xl border border-[#eee] bg-white shadow-sm">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-[#eee] bg-[#fafafa]">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-semibold text-dt-ink"><?= e(__('admin.col_date')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold text-dt-ink"><?= e(__('admin.col_customer')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold text-dt-ink"><?= e(__('admin.col_destination')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold text-dt-ink"><?= e(__('admin.col_package')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold text-dt-ink"><?= e(__('admin.col_price')) ?></th>
                        <th scope="col" class="px-4 py-3 font-semibold text-dt-ink"><?= e(__('admin.col_status')) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <?php
                        $statusKey = 'client.status_' . (string) $reservation['status'];
                        $statusLabel = __($statusKey);
                        if ($statusLabel === $statusKey) {
                            $statusLabel = __('client.status_confirmed');
                        }
                        ?>
                        <tr class="border-b border-[#f3f3f3]">
                            <td class="px-4 py-3 whitespace-nowrap text-dt-muted"><?= e((string) $reservation['purchased_at']) ?></td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-dt-ink"><?= e((string) $reservation['customer_name']) ?></p>
                                <p class="text-xs text-dt-muted"><?= e((string) $reservation['customer_email']) ?></p>
                            </td>
                            <td class="px-4 py-3 text-dt-ink"><?= e((string) $reservation['destination_name']) ?></td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-dt-ink"><?= e((string) $reservation['package_title']) ?></p>
                                <p class="text-xs text-dt-muted"><?= e(__('package.days', ['n' => (string) $reservation['duration_days']])) ?></p>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-dt-ink">
                                R$ <?= e(number_format((float) $reservation['price'], 2, ',', '.')) ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                    <?= e($statusLabel) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php require __DIR__ . '/../../components/pagination.php'; ?>
    <?php endif; ?>
<?php require __DIR__ . '/../../components/footer.php'; ?>
