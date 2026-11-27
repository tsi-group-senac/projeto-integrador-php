<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../backend/bootstrap.php';
require_once __DIR__ . '/../../../backend/services/CardPaymentValidator.php';
require_once __DIR__ . '/../../../backend/controllers/PurchaseController.php';

$purchaseController = new PurchaseController($purchaseService, $csrfService, $authService);
$customer = $purchaseController->requireCustomer();

$packageId = filter_var($_GET['package_id'] ?? $_POST['package_id'] ?? null, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);
$package = null;
$formErrors = [];
$fieldErrors = [];

$old = [
    'card_holder' => '',
    'card_number' => '',
    'card_expiry' => '',
    'card_cvv' => '',
];

if ($packageId === false || $packageId === null) {
    http_response_code(404);
} else {
    $package = $packageModel->findActiveById((int) $packageId);
    if ($package === null) {
        http_response_code(404);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['card_holder'] = trim((string) ($_POST['card_holder'] ?? ''));
    $old['card_number'] = trim((string) ($_POST['card_number'] ?? ''));
    $old['card_expiry'] = trim((string) ($_POST['card_expiry'] ?? ''));
    $old['card_cvv'] = trim((string) ($_POST['card_cvv'] ?? ''));

    $result = $purchaseController->simulate($_POST);

    if ($result['ok']) {
        flash_set('success', __('client.purchase_success'));
        redirect(app_url('/frontend/pages/client/dashboard.php'));
    }

    $code = (int) ($result['code'] ?? 422);
    http_response_code($code);
    if (!empty($result['errors']) && is_array($result['errors'])) {
        $fieldErrors = $result['errors'];
    }
    if (!empty($result['message'])) {
        $formErrors[] = (string) $result['message'];
    }
    if ($formErrors === [] && $fieldErrors === []) {
        $formErrors[] = __('client.purchase_fail');
    }
}

$cancelUrl = $package !== null
    ? app_url('/frontend/pages/package.php?id=' . (int) $package['id'])
    : app_url('/frontend/pages/tours.php');

$activeNav = 'client';
$pageTitle = __('client.purchase_title');
require __DIR__ . '/../../components/head.php';
require __DIR__ . '/../../components/header.php';
?>
<main id="conteudo" class="mx-auto max-w-3xl px-4 py-10">
    <h1 class="text-2xl font-semibold text-dt-ink"><?= e(__('client.purchase_heading')) ?></h1>
    <p class="mt-2 text-sm text-dt-muted"><?= e(__('client.purchase_hello', ['name' => (string) $customer['name']])) ?></p>

    <?php require __DIR__ . '/../../components/messages.php'; ?>

    <?php if ($package === null): ?>
        <div class="mt-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <?= e(__('client.purchase_invalid')) ?>
        </div>
        <a class="mt-4 inline-flex h-9 items-center rounded-full bg-dt-orange px-4 text-sm font-semibold text-white" href="<?= e(app_url('/frontend/pages/tours.php')) ?>">
            <?= e(__('client.purchase_back')) ?>
        </a>
    <?php else: ?>
        <article class="mt-6 rounded-2xl border border-[#eee] bg-white p-5 shadow-sm sm:p-6">
            <p class="text-sm font-semibold text-dt-orange"><?= e((string) $package['destination_name']) ?></p>
            <h2 class="mt-1 text-xl font-semibold text-dt-ink"><?= e((string) $package['title']) ?></h2>
            <p class="mt-2 text-sm text-dt-muted"><?= e((string) $package['summary']) ?></p>
            <p class="mt-3 text-sm font-semibold text-dt-ink">
                <?= e(__('common.days_price', [
                    'price' => number_format((float) $package['price'], 2, ',', '.'),
                    'days' => (string) $package['duration_days'],
                ])) ?>
            </p>
            <p class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-950" role="note">
                <?= e(__('client.purchase_warning')) ?>
            </p>

            <form method="post" class="mt-6 space-y-4" novalidate autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= e($csrfService->token()) ?>">
                <input type="hidden" name="package_id" value="<?= (int) $package['id'] ?>">

                <h3 class="text-base font-semibold text-dt-ink"><?= e(__('card.section_title')) ?></h3>

                <div>
                    <label class="mb-1 block text-xs font-medium text-dt-muted" for="card_holder"><?= e(__('card.holder')) ?></label>
                    <input
                        class="h-10 w-full rounded-lg border border-[#e5e5e5] px-3 text-sm outline-none focus:border-dt-orange"
                        id="card_holder"
                        name="card_holder"
                        type="text"
                        required
                        maxlength="80"
                        placeholder="<?= e(__('card.holder_ph')) ?>"
                        value="<?= e($old['card_holder']) ?>"
                    >
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-dt-muted" for="card_number"><?= e(__('card.number')) ?></label>
                    <input
                        class="h-10 w-full rounded-lg border border-[#e5e5e5] px-3 text-sm tracking-wider outline-none focus:border-dt-orange"
                        id="card_number"
                        name="card_number"
                        type="text"
                        inputmode="numeric"
                        required
                        maxlength="23"
                        placeholder="ACCT-000003"
                        value="<?= e($old['card_number']) ?>"
                    >
                    <p class="mt-1 text-[11px] text-dt-muted"><?= e(__('card.number_hint')) ?></p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-dt-muted" for="card_expiry"><?= e(__('card.expiry')) ?></label>
                        <input
                            class="h-10 w-full rounded-lg border border-[#e5e5e5] px-3 text-sm outline-none focus:border-dt-orange"
                            id="card_expiry"
                            name="card_expiry"
                            type="text"
                            inputmode="numeric"
                            required
                            maxlength="5"
                            placeholder="MM/AA"
                            value="<?= e($old['card_expiry']) ?>"
                        >
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-dt-muted" for="card_cvv"><?= e(__('card.cvv')) ?></label>
                        <input
                            class="h-10 w-full rounded-lg border border-[#e5e5e5] px-3 text-sm outline-none focus:border-dt-orange"
                            id="card_cvv"
                            name="card_cvv"
                            type="password"
                            inputmode="numeric"
                            required
                            maxlength="4"
                            placeholder="123"
                            value="<?= e($old['card_cvv']) ?>"
                        >
                        <p class="mt-1 text-[11px] text-dt-muted"><?= e(__('card.cvv_hint')) ?></p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button class="inline-flex h-10 items-center justify-center rounded-full bg-dt-orange px-5 text-sm font-semibold text-white hover:brightness-95" type="submit">
                        <?= e(__('client.purchase_confirm')) ?>
                    </button>
                    <a class="text-sm font-medium text-dt-orange hover:underline" href="<?= e($cancelUrl) ?>">
                        <?= e(__('client.purchase_cancel')) ?>
                    </a>
                </div>
            </form>
        </article>
    <?php endif; ?>
<?php require __DIR__ . '/../../components/footer.php'; ?>
