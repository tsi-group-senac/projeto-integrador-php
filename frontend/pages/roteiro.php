<?php

declare(strict_types=1);

require_once __DIR__ . '/../../backend/bootstrap.php';

/** @var array<string, array<string, mixed>> $catalog */
$catalog = require __DIR__ . '/../../backend/data/destinations-catalog.php';

$slug = strtolower(trim((string) ($_GET['destino'] ?? '')));
$slug = str_replace([' ', '_'], '-', $slug);

$aliases = [
    'rio' => 'rio-de-janeiro',
    'foz' => 'foz-do-iguacu',
    'iguacu' => 'foz-do-iguacu',
    'iguaçu' => 'foz-do-iguacu',
];
$slug = $aliases[$slug] ?? $slug;

$roteiro = $catalog[$slug] ?? null;

if ($roteiro === null) {
    // Allow ?destino=Dubai (city name) as fallback.
    $needle = mb_strtolower((string) ($_GET['destino'] ?? ''));
    foreach ($catalog as $item) {
        if (mb_strtolower((string) $item['city']) === $needle) {
            $roteiro = $item;
            $slug = (string) $item['slug'];
            break;
        }
    }
}

$user = current_user();
$dbPackage = null;

if ($roteiro !== null) {
    try {
        foreach ($packageService->listActive() as $candidate) {
            if (mb_strtolower((string) $candidate['destination_name']) === mb_strtolower((string) $roteiro['city'])) {
                $dbPackage = $candidate;
                break;
            }
        }
    } catch (PDOException $exception) {
        error_log('Falha ao cruzar roteiro com pacote: ' . $exception->getMessage());
    }
}

if ($roteiro === null) {
    http_response_code(404);
}

$price = $dbPackage !== null
    ? (float) $dbPackage['price']
    : (float) ($roteiro['price'] ?? 0);
$days = $dbPackage !== null
    ? (int) $dbPackage['duration_days']
    : (int) ($roteiro['duration_days'] ?? 0);
$summary = $dbPackage !== null
    ? (string) $dbPackage['summary']
    : (string) ($roteiro['summary'] ?? '');
$description = $dbPackage !== null
    ? (string) $dbPackage['description']
    : (string) ($roteiro['description'] ?? '');
$title = $dbPackage !== null
    ? (string) $dbPackage['title']
    : ((string) ($roteiro['city'] ?? '') . ' — Destyn Trips');

$image = app_url((string) ($roteiro['image'] ?? '/frontend/assets/images/rio.jpg'));
if ($dbPackage !== null && !empty($dbPackage['image_path'])) {
    $imagePath = (string) $dbPackage['image_path'];
    $image = str_starts_with($imagePath, '/frontend/') ? app_url($imagePath) : $imagePath;
}

$buyUrl = app_url('/frontend/pages/login.php');
$buyLabel = __('package.login_to_buy');
$showRegister = true;

if ($dbPackage !== null) {
    if ($user !== null && ($user['role'] ?? '') === 'customer') {
        $buyUrl = app_url('/frontend/pages/client/purchase.php?package_id=' . (int) $dbPackage['id']);
        $buyLabel = __('package.buy');
        $showRegister = false;
    } elseif ($user !== null && ($user['role'] ?? '') === 'admin') {
        $buyUrl = app_url('/frontend/pages/admin/package-form.php?id=' . (int) $dbPackage['id']);
        $buyLabel = __('package.edit_admin');
        $showRegister = false;
    } else {
        // Visitante: login e depois volta para a compra deste pacote.
        $buyUrl = app_url('/frontend/pages/login.php');
        $buyLabel = __('package.login_to_buy');
        $showRegister = true;
    }
} elseif ($user !== null && ($user['role'] ?? '') === 'admin') {
    $buyUrl = app_url('/frontend/pages/admin/packages.php');
    $buyLabel = __('package.edit_admin');
    $showRegister = false;
}

$activeNav = 'tours';
$pageTitle = $roteiro === null
    ? __('roteiro.missing_title')
    : ((string) $roteiro['city'] . ' | Destyn Trips');
$pageDescription = $roteiro === null ? __('meta.default_description') : $summary;

require __DIR__ . '/../components/head.php';
require __DIR__ . '/../components/header.php';
?>
<main id="conteudo">
    <?php if ($roteiro === null): ?>
        <section class="mx-auto max-w-content px-4 py-16 text-center lg:px-0">
            <h1 class="text-2xl font-semibold"><?= e(__('roteiro.not_found')) ?></h1>
            <p class="mt-3 text-dt-muted"><?= e(__('roteiro.not_found_text')) ?></p>
            <a class="mt-5 inline-flex h-9 items-center rounded-full bg-dt-orange px-4 text-sm font-semibold text-white" href="<?= e(app_url('/frontend/pages/tours.php')) ?>">
                <?= e(__('package.back')) ?>
            </a>
        </section>
    <?php else: ?>
        <section class="relative h-[260px] overflow-hidden sm:h-[340px]">
            <img class="absolute inset-0 h-full w-full object-cover" src="<?= e($image) ?>" alt="<?= e((string) $roteiro['city']) ?>">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/35 to-black/15"></div>
            <div class="relative z-10 mx-auto flex h-full max-w-content flex-col justify-end px-4 pb-8 lg:px-0">
                <a class="mb-3 w-fit text-xs font-medium text-white/85 hover:text-white" href="<?= e(app_url('/frontend/pages/tours.php')) ?>">
                    <?= e(__('package.back')) ?>
                </a>
                <p class="font-script text-[40px] leading-none text-dt-orange sm:text-[52px]"><?= e((string) $roteiro['city']) ?></p>
                <p class="mt-1 text-sm font-semibold tracking-[0.18em] text-white/90"><?= e(strtoupper((string) $roteiro['country'])) ?></p>
                <h1 class="mt-2 max-w-3xl text-[24px] font-semibold text-white sm:text-[32px]"><?= e($title) ?></h1>
            </div>
        </section>

        <section class="mx-auto grid max-w-content gap-8 px-4 py-10 lg:grid-cols-[1.5fr_0.7fr] lg:gap-10 lg:px-0 lg:py-12">
            <div>
                <h2 class="text-xl font-semibold text-dt-ink"><?= e(__('package.about_place')) ?></h2>
                <p class="mt-3 text-[15px] leading-7 text-dt-muted"><?= e($summary) ?></p>

                <?php if (!empty($roteiro['highlights']) && is_array($roteiro['highlights'])): ?>
                    <h2 class="mt-8 text-xl font-semibold text-dt-ink"><?= e(__('roteiro.highlights')) ?></h2>
                    <ul class="mt-3 grid gap-2 sm:grid-cols-2">
                        <?php foreach ($roteiro['highlights'] as $highlight): ?>
                            <li class="rounded-xl border border-[#eee] bg-white px-4 py-3 text-sm font-medium text-dt-ink">
                                <?= e((string) $highlight) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <h2 class="mt-8 text-xl font-semibold text-dt-ink"><?= e(__('package.itinerary')) ?></h2>
                <p class="mt-3 whitespace-pre-line text-[15px] leading-7 text-dt-ink/90"><?= e($description) ?></p>
            </div>

            <aside class="h-fit rounded-2xl border border-[#eee] bg-white p-5 shadow-sm lg:sticky lg:top-6">
                <p class="text-xs font-medium uppercase tracking-wide text-dt-muted"><?= e(__('package.price_label')) ?></p>
                <p class="mt-1 text-[28px] font-bold text-dt-ink">R$ <?= e(number_format($price, 2, ',', '.')) ?></p>

                <dl class="mt-5 space-y-2 border-t border-[#f0f0f0] pt-4 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-dt-muted"><?= e(__('package.duration_label')) ?></dt>
                        <dd class="font-semibold text-dt-ink"><?= e(__('package.days', ['n' => (string) $days])) ?></dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-dt-muted"><?= e(__('roteiro.country')) ?></dt>
                        <dd class="font-semibold text-dt-ink"><?= e((string) $roteiro['country']) ?></dd>
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
