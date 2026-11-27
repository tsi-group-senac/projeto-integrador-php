<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../backend/bootstrap.php';
require_once __DIR__ . '/../../../backend/controllers/AdminController.php';

$adminController = new AdminController($adminService, $authService);
$admin = $adminController->requireAdmin();
$totals = [];
$recentReservations = [];
$loadError = false;

try {
    $totals = $adminController->dashboard();
    $recentReservations = array_slice($adminController->reservationsPage(1, 5)['items'], 0, 5);
} catch (PDOException $exception) {
    error_log('Falha ao carregar dashboard admin: ' . $exception->getMessage());
    $loadError = true;
}

$activeNav = 'admin';
$pageTitle = __('admin.dashboard_title');
require __DIR__ . '/../../components/head.php';
require __DIR__ . '/../../components/header.php';
?>
<main id="conteudo" class="mx-auto max-w-6xl px-4 py-10">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-dt-ink"><?= e(__('admin.dashboard_heading')) ?></h1>
            <p class="mt-2 text-sm text-dt-muted"><?= e(__('admin.dashboard_hello', ['name' => (string) $admin['name']])) ?></p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a class="inline-flex h-9 items-center rounded-full bg-dt-orange px-4 text-sm font-semibold text-white hover:brightness-95" href="<?= e(app_url('/frontend/pages/admin/packages.php')) ?>"><?= e(__('admin.packages')) ?></a>
            <a class="inline-flex h-9 items-center rounded-full border border-dt-orange px-4 text-sm font-semibold text-dt-orange hover:bg-[#fff8ee]" href="<?= e(app_url('/frontend/pages/admin/users.php')) ?>"><?= e(__('admin.customers')) ?></a>
            <a class="inline-flex h-9 items-center rounded-full border border-dt-orange px-4 text-sm font-semibold text-dt-orange hover:bg-[#fff8ee]" href="<?= e(app_url('/frontend/pages/admin/reservations.php')) ?>"><?= e(__('admin.reservations')) ?></a>
        </div>
    </div>

    <?php require __DIR__ . '/../../components/messages.php'; ?>

    <?php if ($loadError): ?>
        <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <?= e(__('admin.metrics_error')) ?>
        </div>
    <?php else: ?>
        <div class="grid gap-4 sm:grid-cols-2">
            <a href="<?= e(app_url('/frontend/pages/admin/users.php')) ?>" class="rounded-2xl border border-[#eee] bg-white p-6 shadow-sm transition hover:border-dt-orange">
                <h2 class="text-xs font-semibold uppercase tracking-wide text-dt-orange"><?= e(__('admin.metric_customers')) ?></h2>
                <p class="mt-3 text-4xl font-bold text-dt-ink"><?= (int) ($totals['customers'] ?? 0) ?></p>
                <p class="mt-2 text-sm text-dt-muted"><?= e(__('admin.metric_customers_cta')) ?></p>
            </a>
            <a href="<?= e(app_url('/frontend/pages/admin/reservations.php')) ?>" class="rounded-2xl border border-[#eee] bg-white p-6 shadow-sm transition hover:border-dt-orange">
                <h2 class="text-xs font-semibold uppercase tracking-wide text-dt-orange"><?= e(__('admin.metric_purchases')) ?></h2>
                <p class="mt-3 text-4xl font-bold text-dt-ink"><?= (int) ($totals['purchases'] ?? 0) ?></p>
                <p class="mt-2 text-sm text-dt-muted"><?= e(__('admin.metric_purchases_cta')) ?></p>
            </a>
        </div>

        <section class="mt-10">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="text-lg font-semibold text-dt-ink"><?= e(__('admin.recent_reservations')) ?></h2>
                <a class="text-sm font-semibold text-dt-orange hover:underline" href="<?= e(app_url('/frontend/pages/admin/reservations.php')) ?>">
                    <?= e(__('admin.see_all_reservations')) ?>
                </a>
            </div>

            <?php if ($recentReservations === []): ?>
                <div class="rounded-xl border border-[#eee] bg-white px-4 py-6 text-sm text-dt-muted" role="status">
                    <?= e(__('admin.reservations_empty')) ?>
                </div>
            <?php else: ?>
                <ul class="space-y-3">
                    <?php foreach ($recentReservations as $reservation): ?>
                        <li class="rounded-xl border border-[#eee] bg-white px-4 py-4 shadow-sm">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-dt-orange"><?= e((string) $reservation['destination_name']) ?></p>
                                    <p class="font-medium text-dt-ink"><?= e((string) $reservation['package_title']) ?></p>
                                    <p class="mt-1 text-sm text-dt-muted">
                                        <?= e((string) $reservation['customer_name']) ?> · <?= e((string) $reservation['customer_email']) ?>
                                    </p>
                                </div>
                                <div class="text-right text-sm text-dt-muted">
                                    <p><?= e((string) $reservation['purchased_at']) ?></p>
                                    <p class="mt-1 font-semibold text-dt-ink">
                                        R$ <?= e(number_format((float) $reservation['price'], 2, ',', '.')) ?>
                                    </p>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    <?php endif; ?>
<?php require __DIR__ . '/../../components/footer.php'; ?>
