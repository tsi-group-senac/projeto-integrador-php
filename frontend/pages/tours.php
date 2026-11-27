<?php

declare(strict_types=1);

require_once __DIR__ . '/../../backend/bootstrap.php';

$location = trim((string) ($_GET['location'] ?? ''));
$packages = [];
$loadError = false;

try {
    $packages = $packageService->listActive();
    if ($location !== '') {
        $needle = mb_strtolower($location);
        $packages = array_values(array_filter(
            $packages,
            static function (array $package) use ($needle): bool {
                $haystack = mb_strtolower(
                    (string) $package['title'] . ' ' . (string) $package['destination_name'] . ' ' . (string) $package['summary']
                );

                return str_contains($haystack, $needle);
            }
        ));
    }
} catch (PDOException $exception) {
    error_log('Falha ao listar tours: ' . $exception->getMessage());
    $loadError = true;
}

$featured = [
    ['slug' => 'dubai', 'city' => 'Dubai', 'country' => 'UAE', 'image' => app_url('/frontend/assets/images/dest-dubai.jpg')],
    ['slug' => 'bali', 'city' => 'Bali', 'country' => 'Indonesia', 'image' => app_url('/frontend/assets/images/dest-bali.jpg')],
    ['slug' => 'sydney', 'city' => 'Sydney', 'country' => 'Australia', 'image' => app_url('/frontend/assets/images/dest-sydney.jpg')],
    ['slug' => 'phuket', 'city' => 'Phuket', 'country' => 'Indonesia', 'image' => app_url('/frontend/assets/images/dest-phuket.jpg')],
    ['slug' => 'shanghai', 'city' => 'Shanghai', 'country' => 'China', 'image' => app_url('/frontend/assets/images/dest-shanghai.jpg')],
    ['slug' => 'london', 'city' => 'London', 'country' => 'UK', 'image' => app_url('/frontend/assets/images/dest-london.jpg')],
    ['slug' => 'paris', 'city' => 'Paris', 'country' => 'France', 'image' => app_url('/frontend/assets/images/dest-paris.jpg')],
    ['slug' => 'maldives', 'city' => 'Maldives', 'country' => 'Maldives', 'image' => app_url('/frontend/assets/images/dest-maldives.jpg')],
];

$activeNav = 'tours';
$pageTitle = __('meta.tours_title');
require __DIR__ . '/../components/head.php';
require __DIR__ . '/../components/header.php';
?>
<main id="conteudo">
    <section class="relative h-[360px] overflow-hidden sm:h-[420px]">
        <img class="absolute inset-0 h-full w-full object-cover" src="<?= e(app_url('/frontend/assets/images/gallery-wide.png')) ?>" alt="<?= e(__('meta.tours_title')) ?>">
        <div class="absolute inset-0 bg-black/35"></div>
        <div class="relative z-10 flex h-full items-center justify-center">
            <h1 class="text-[48px] font-semibold text-white sm:text-[64px]">
                <span class="text-dt-orange"><?= e(__('tours.hero_all')) ?></span> <?= e(__('tours.hero_tours')) ?>
            </h1>
        </div>
    </section>

    <section class="mx-auto max-w-content px-4 py-16 lg:px-0">
        <?php
        $eyebrow = '';
        $titlePrefix = __('tours.featured_title');
        $titleAccent = __('tours.featured_accent');
        $align = 'center';
        require __DIR__ . '/../components/section-heading.php';
        ?>

        <div class="mt-10 grid gap-4 md:grid-cols-6">
            <?php foreach ($featured as $index => $item): ?>
                <?php
                $destinationImage = $item['image'];
                $destinationCity = $item['city'];
                $destinationCountry = $item['country'];
                $destinationHref = app_url('/frontend/pages/roteiro.php?destino=' . rawurlencode($item['slug']));
                $destinationClass = in_array($index, [0, 1, 5, 6], true) ? 'md:col-span-3 min-h-[320px]' : 'md:col-span-2 min-h-[200px]';
                require __DIR__ . '/../components/destination-card.php';
                ?>
            <?php endforeach; ?>
        </div>

        <div class="mt-8 flex justify-center">
            <a class="inline-flex h-9 items-center rounded-full border border-[#d0d0d0] px-4 text-sm font-medium text-dt-ink hover:border-dt-orange hover:text-dt-orange" href="#pacotes-db">
                <?= e(__('tours.load_more')) ?>
            </a>
        </div>

        <div id="pacotes-db" class="mt-16">
            <h2 class="mb-6 text-center text-[28px] font-semibold"><?= e(__('tours.available')) ?></h2>
            <?php if ($loadError): ?>
                <div class="rounded-[12px] border border-red-300 bg-red-50 px-4 py-3 text-red-900" role="alert"><?= e(__('tours.load_error')) ?></div>
            <?php elseif ($packages === []): ?>
                <div class="rounded-[12px] border border-[#eee] px-4 py-6 text-center text-dt-muted" role="status"><?= e(__('tours.empty')) ?></div>
            <?php else: ?>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($packages as $package): ?>
                        <?php require __DIR__ . '/../components/package-card.php'; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php require __DIR__ . '/../components/subscribe-banner.php'; ?>
<?php require __DIR__ . '/../components/footer.php'; ?>
