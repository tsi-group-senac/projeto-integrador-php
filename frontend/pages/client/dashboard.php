<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../backend/bootstrap.php';

$customer = $authService->requireRole('customer');
$purchases = [];
$loadError = false;

try {
    $purchases = $purchaseService->listByUser((int) $customer['id']);
} catch (PDOException $exception) {
    error_log('Falha ao carregar histórico do cliente: ' . $exception->getMessage());
    $loadError = true;
}

$pageTitle = __('client.dashboard_title');
require __DIR__ . '/../../components/head.php';
require __DIR__ . '/../../components/header.php';
?>
<main id="conteudo" class="mx-auto max-w-6xl px-4 py-10">
    <h1 class="font-display text-3xl font-bold"><?= e(__('client.heading')) ?></h1>
    <p class="mt-2 text-brand-900/70"><?= e(__('client.subtitle')) ?></p>

    <?php require __DIR__ . '/../../components/messages.php'; ?>

    <section class="mt-8 grid gap-4 sm:grid-cols-2">
        <article class="rounded-xl border border-brand-100 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-brand-600"><?= e(__('client.profile')) ?></h2>
            <p class="mt-3 text-lg font-semibold"><?= e((string) $customer['name']) ?></p>
            <p class="text-brand-900/70"><?= e((string) $customer['email']) ?></p>
        </article>
        <article class="rounded-xl border border-brand-100 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-brand-600"><?= e(__('client.trips')) ?></h2>
            <p class="mt-3 font-display text-4xl font-bold"><?= $loadError ? '—' : count($purchases) ?></p>
        </article>
    </section>

    <section class="mt-10">
        <h2 class="font-display text-2xl font-bold"><?= e(__('client.history')) ?></h2>

        <?php if ($loadError): ?>
            <div class="mt-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-red-900" role="alert">
                <?= e(__('client.history_error')) ?>
            </div>
        <?php elseif ($purchases === []): ?>
            <div class="mt-4 rounded-md border border-brand-200 bg-white px-4 py-6" role="status">
                <p class="font-semibold"><?= e(__('client.empty_title')) ?></p>
                <p class="mt-2 text-brand-900/70"><?= e(__('client.empty_text')) ?></p>
                <a class="mt-4 inline-flex rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white" href="<?= e(app_url('/frontend/index.php#pacotes')) ?>">
                    <?= e(__('client.see_tours')) ?>
                </a>
            </div>
        <?php else: ?>
            <ul class="mt-4 space-y-4">
                <?php foreach ($purchases as $purchase): ?>
                    <li class="rounded-xl border border-brand-100 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-brand-600"><?= e((string) $purchase['destination_name']) ?></p>
                                <h3 class="font-display text-xl font-bold"><?= e((string) $purchase['package_title']) ?></h3>
                                <p class="mt-1 text-brand-900/70"><?= e((string) $purchase['package_summary']) ?></p>
                            </div>
                            <div class="text-sm text-brand-900/70">
                                <p><?= e((string) $purchase['purchased_at']) ?></p>
                                <?php
                                $statusKey = 'client.status_' . (string) $purchase['status'];
                                $statusLabel = __($statusKey);
                                if ($statusLabel === $statusKey) {
                                    $statusLabel = __('client.status_confirmed');
                                }
                                ?>
                                <p><?= e(__('client.status', ['status' => $statusLabel])) ?></p>
                                <?php if ((int) $purchase['package_is_active'] !== 1): ?>
                                    <p class="font-semibold text-amber-800"><?= e(__('client.inactive_note')) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <p class="mt-3 text-sm font-semibold">
                            <?= e(__('common.days_price', [
                                'price' => number_format((float) $purchase['price'], 2, ',', '.'),
                                'days' => (string) $purchase['duration_days'],
                            ])) ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
<?php require __DIR__ . '/../../components/footer.php'; ?>
