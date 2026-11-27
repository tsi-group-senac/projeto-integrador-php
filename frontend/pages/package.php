<?php

declare(strict_types=1);

require_once __DIR__ . '/../../backend/bootstrap.php';

$packageId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);
$package = null;
$packageError = false;
$user = current_user();

if ($packageId !== false && $packageId !== null) {
    try {
        $package = $packageModel->findActiveById($packageId);
    } catch (PDOException $exception) {
        error_log('Falha ao carregar pacote: ' . $exception->getMessage());
        $packageError = true;
    }
}

if ($packageError) {
    http_response_code(500);
} elseif ($package === null) {
    http_response_code(404);
}

$activeNav = 'tours';
$pageTitle = $package === null
    ? __('meta.package_missing')
    : ((string) $package['title'] . ' | Destyn Trips');
$pageDescription = $package === null
    ? __('meta.default_description')
    : (string) $package['summary'];

$image = app_url('/frontend/assets/images/rio.jpg');
if ($package !== null) {
    $image = !empty($package['image_path'])
        ? (string) $package['image_path']
        : $image;
    if (str_starts_with($image, '/frontend/')) {
        $image = app_url($image);
    }
}

$buyUrl = app_url('/frontend/pages/login.php');
$buyLabel = __('package.login_to_buy');
$showRegister = true;

if ($user !== null && ($user['role'] ?? '') === 'customer') {
    $buyUrl = app_url('/frontend/pages/client/purchase.php?package_id=' . (int) ($package['id'] ?? 0));
    $buyLabel = __('package.buy');
    $showRegister = false;
} elseif ($user !== null && ($user['role'] ?? '') === 'admin') {
    $buyUrl = app_url('/frontend/pages/admin/package-form.php?id=' . (int) ($package['id'] ?? 0));
    $buyLabel = __('package.edit_admin');
    $showRegister = false;
}

require __DIR__ . '/../components/head.php';
require __DIR__ . '/../components/header.php';
?>
<main id="conteudo">
    <?php if ($packageError): ?>
        <section class="mx-auto max-w-content px-4 py-16 text-center lg:px-0">
            <h1 class="text-2xl font-semibold"><?= e(__('package.load_error')) ?></h1>
            <a class="mt-5 inline-flex h-9 items-center rounded-full bg-dt-orange px-4 text-sm font-semibold text-white" href="<?= e(app_url('/frontend/pages/tours.php')) ?>"><?= e(__('package.back')) ?></a>
        </section>
    <?php elseif ($package === null): ?>
        <section class="mx-auto max-w-content px-4 py-16 text-center lg:px-0">
            <h1 class="text-2xl font-semibold"><?= e(__('package.not_found')) ?></h1>
            <a class="mt-5 inline-flex h-9 items-center rounded-full bg-dt-orange px-4 text-sm font-semibold text-white" href="<?= e(app_url('/frontend/pages/tours.php')) ?>"><?= e(__('package.back')) ?></a>
        </section>
    <?php else: ?>
        <section class="relative h-[260px] overflow-hidden sm:h-[340px]">
            <img class="absolute inset-0 h-full w-full object-cover" src="<?= e($image) ?>" alt="<?= e((string) $package['title']) ?>">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/35 to-black/15"></div>
            <div class="relative z-10 mx-auto flex h-full max-w-content flex-col justify-end px-4 pb-8 lg:px-0">
                <a class="mb-3 w-fit text-xs font-medium text-white/85 hover:text-white" href="<?= e(app_url('/frontend/pages/tours.php')) ?>"><?= e(__('package.back')) ?></a>
                <p class="font-script text-[40px] leading-none text-dt-orange sm:text-[52px]"><?= e((string) $package['destination_name']) ?></p>
                <h1 class="mt-1 max-w-3xl text-[26px] font-semibold text-white sm:text-[34px]"><?= e((string) $package['title']) ?></h1>
            </div>
        </section>

        <section class="mx-auto grid max-w-content gap-8 px-4 py-10 lg:grid-cols-[1.5fr_0.7fr] lg:gap-10 lg:px-0 lg:py-12">
            <div>
                <h2 class="text-xl font-semibold text-dt-ink"><?= e(__('package.about_place')) ?></h2>
                <p class="mt-3 text-[15px] leading-7 text-dt-muted"><?= e((string) $package['summary']) ?></p>

                <h2 class="mt-8 text-xl font-semibold text-dt-ink"><?= e(__('package.itinerary')) ?></h2>
                <p class="mt-3 whitespace-pre-line text-[15px] leading-7 text-dt-ink/90"><?= e((string) $package['description']) ?></p>
            </div>

            <aside class="h-fit rounded-2xl border border-[#eee] bg-white p-5 shadow-sm lg:sticky lg:top-6">
                <p class="text-xs font-medium uppercase tracking-wide text-dt-muted"><?= e(__('package.price_label')) ?></p>
                <p class="mt-1 text-[28px] font-bold text-dt-ink">R$ <?= e(number_format((float) $package['price'], 2, ',', '.')) ?></p>

                <dl class="mt-5 space-y-2 border-t border-[#f0f0f0] pt-4 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-dt-muted"><?= e(__('package.duration_label')) ?></dt>
                        <dd class="font-semibold text-dt-ink"><?= e(__('package.days', ['n' => (string) $package['duration_days']])) ?></dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-dt-muted"><?= e(__('nav.tours')) ?></dt>
                        <dd class="font-semibold text-dt-ink"><?= e((string) $package['destination_name']) ?></dd>
                    </div>
                </dl>

                <a class="mt-5 inline-flex h-10 w-full items-center justify-center rounded-full bg-dt-orange text-sm font-semibold text-white hover:brightness-95" href="<?= e($buyUrl) ?>">
                    <?= e($buyLabel) ?>
                </a>

                <?php if ($showRegister): ?>
                    <a class="mt-2 inline-flex h-9 w-full items-center justify-center rounded-full border border-dt-orange text-sm font-medium text-dt-orange hover:bg-[#fff8ee]" href="<?= e(app_url('/frontend/pages/register.php')) ?>">
                        <?= e(__('nav.register')) ?>
                    </a>
                <?php endif; ?>

                <p class="mt-3 text-center text-[12px] leading-5 text-dt-muted"><?= e(__('package.buy_note')) ?></p>
            </aside>
        </section>

        <?php require __DIR__ . '/../components/subscribe-banner.php'; ?>
    <?php endif; ?>
<?php require __DIR__ . '/../components/footer.php'; ?>
