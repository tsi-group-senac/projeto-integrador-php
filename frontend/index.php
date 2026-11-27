<?php

declare(strict_types=1);

require_once __DIR__ . '/../backend/bootstrap.php';

$packages = [];
$catalogError = false;

try {
    $packages = $packageService->listActive();
} catch (PDOException $exception) {
    error_log('Falha ao carregar catálogo: ' . $exception->getMessage());
    $catalogError = true;
}

$heroImages = [
    app_url('/frontend/assets/images/hero-travel-1.jpg'),
    app_url('/frontend/assets/images/hero-travel-2.jpg'),
    app_url('/frontend/assets/images/hero-travel-3.jpg'),
];

$featuredStatic = [
    ['slug' => 'dubai', 'city' => 'Dubai', 'country' => 'UAE', 'image' => app_url('/frontend/assets/images/dest-dubai.jpg'), 'class' => 'min-h-[335px]'],
    ['slug' => 'bali', 'city' => 'Bali', 'country' => 'Indonesia', 'image' => app_url('/frontend/assets/images/dest-bali.jpg'), 'class' => 'min-h-[335px]'],
    ['slug' => 'sydney', 'city' => 'Sydney', 'country' => 'Australia', 'image' => app_url('/frontend/assets/images/dest-sydney.jpg'), 'class' => 'min-h-[218px]'],
    ['slug' => 'phuket', 'city' => 'Phuket', 'country' => 'Indonesia', 'image' => app_url('/frontend/assets/images/dest-phuket.jpg'), 'class' => 'min-h-[218px]'],
    ['slug' => 'shanghai', 'city' => 'Shanghai', 'country' => 'China', 'image' => app_url('/frontend/assets/images/dest-shanghai.jpg'), 'class' => 'min-h-[218px]'],
    ['slug' => 'london', 'city' => 'London', 'country' => 'UK', 'image' => app_url('/frontend/assets/images/dest-london.jpg'), 'class' => 'min-h-[335px]'],
    ['slug' => 'paris', 'city' => 'Paris', 'country' => 'France', 'image' => app_url('/frontend/assets/images/dest-paris.jpg'), 'class' => 'min-h-[335px]'],
];

$activeNav = 'home';
$pageTitle = __('meta.home_title');
require __DIR__ . '/components/head.php';
require __DIR__ . '/components/header.php';
?>
<main id="conteudo">
    <section class="relative overflow-hidden px-4 pb-16 pt-10 lg:px-[160px] lg:pb-20 lg:pt-14">
        <div class="mx-auto grid max-w-site items-center gap-10 lg:grid-cols-2">
            <div>
                <h1 class="max-w-xl text-[42px] font-semibold leading-[1.15] text-dt-ink sm:text-[54px]">
                    <?= e(__('home.hero_before')) ?>
                    <span class="dt-underline-memories text-dt-orange"><?= e(__('home.hero_accent')) ?></span>
                </h1>
                <p class="mt-5 max-w-md text-[15px] leading-7 text-dt-muted">
                    <?= e(__('home.hero_text')) ?>
                </p>
                <a class="mt-6 inline-flex h-9 items-center justify-center rounded-full bg-dt-orange px-5 text-sm font-semibold text-white hover:brightness-95" href="<?= e(app_url('/frontend/pages/tours.php')) ?>">
                    <?= e(__('nav.tours')) ?>
                </a>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <?php foreach ($heroImages as $index => $src): ?>
                    <div class="<?= $index === 1 ? 'mt-10' : ($index === 2 ? 'mt-4' : '') ?>">
                        <img class="h-[280px] w-full rounded-[20px] object-cover shadow-lg sm:h-[350px]" src="<?= e($src) ?>" alt="<?= e(__('nav.tours')) ?> <?= $index + 1 ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-content px-4 py-16 lg:px-0">
        <div class="grid gap-8 lg:grid-cols-[280px_1fr]">
            <div>
                <?php
                $eyebrow = __('home.services_eyebrow');
                $titlePrefix = __('home.services_title');
                $titleAccent = '';
                $align = 'left';
                require __DIR__ . '/components/section-heading.php';
                ?>
            </div>
            <div class="grid gap-6 sm:grid-cols-3">
                <?php
                $services = [
                    ['icon' => app_url('/frontend/assets/images/icon-weather.png'), 'title' => __('home.service_weather_title'), 'text' => __('home.service_weather_text')],
                    ['icon' => app_url('/frontend/assets/images/icon-guide.png'), 'title' => __('home.service_guide_title'), 'text' => __('home.service_guide_text')],
                    ['icon' => app_url('/frontend/assets/images/icon-custom.png'), 'title' => __('home.service_custom_title'), 'text' => __('home.service_custom_text')],
                ];
                foreach ($services as $service):
                    $serviceIcon = $service['icon'];
                    $serviceTitle = $service['title'];
                    $serviceText = $service['text'];
                    require __DIR__ . '/components/service-card.php';
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <section id="pacotes" class="mx-auto max-w-content px-4 py-10 lg:px-0">
        <?php
        $eyebrow = __('home.explore_eyebrow');
        $titlePrefix = __('home.explore_title');
        $titleAccent = __('home.explore_accent');
        $align = 'center';
        require __DIR__ . '/components/section-heading.php';
        ?>

        <div class="mt-10 grid gap-4 md:grid-cols-3">
            <?php foreach (array_slice($featuredStatic, 0, 2) as $item): ?>
                <?php
                $destinationImage = $item['image'];
                $destinationCity = $item['city'];
                $destinationCountry = $item['country'];
                $destinationClass = $item['class'];
                $destinationHref = app_url('/frontend/pages/roteiro.php?destino=' . rawurlencode($item['slug']));
                require __DIR__ . '/components/destination-card.php';
                ?>
            <?php endforeach; ?>
            <div class="grid gap-4">
                <?php foreach (array_slice($featuredStatic, 2, 3) as $item): ?>
                    <?php
                    $destinationImage = $item['image'];
                    $destinationCity = $item['city'];
                    $destinationCountry = $item['country'];
                    $destinationClass = $item['class'];
                    $destinationHref = app_url('/frontend/pages/roteiro.php?destino=' . rawurlencode($item['slug']));
                    require __DIR__ . '/components/destination-card.php';
                    ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <?php foreach (array_slice($featuredStatic, 5, 2) as $item): ?>
                <?php
                $destinationImage = $item['image'];
                $destinationCity = $item['city'];
                $destinationCountry = $item['country'];
                $destinationClass = $item['class'];
                $destinationHref = app_url('/frontend/pages/roteiro.php?destino=' . rawurlencode($item['slug']));
                require __DIR__ . '/components/destination-card.php';
                ?>
            <?php endforeach; ?>
        </div>

        <div class="mt-12">
            <h3 class="mb-6 text-center text-[28px] font-semibold text-dt-ink"><?= e(__('home.packages_title')) ?></h3>
            <?php require __DIR__ . '/components/messages.php'; ?>
            <?php if ($catalogError): ?>
                <div class="rounded-[12px] border border-red-300 bg-red-50 px-4 py-3 text-red-900" role="alert"><?= e(__('home.catalog_error')) ?></div>
            <?php elseif ($packages === []): ?>
                <div class="rounded-[12px] border border-[#eee] bg-white px-4 py-6 text-center text-dt-muted" role="status"><?= e(__('home.catalog_empty')) ?></div>
            <?php else: ?>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($packages as $package): ?>
                        <?php require __DIR__ . '/components/package-card.php'; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php require __DIR__ . '/components/experience-stats.php'; ?>
    <?php require __DIR__ . '/components/gallery-grid.php'; ?>

    <section class="mx-auto max-w-content px-4 py-16 lg:px-0">
        <?php
        $eyebrow = __('home.fans_eyebrow');
        $titlePrefix = __('home.fans_title');
        $titleAccent = '';
        $align = 'center';
        require __DIR__ . '/components/section-heading.php';
        ?>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            <?php
            $testimonials = [
                ['avatar' => app_url('/frontend/assets/images/avatar-1.png'), 'name' => 'Ana Souza', 'role' => __('home.testimonial_role'), 'text' => __('home.testimonial_1')],
                ['avatar' => app_url('/frontend/assets/images/avatar-2.png'), 'name' => 'Kani Diego', 'role' => __('home.testimonial_role'), 'text' => __('home.testimonial_2')],
                ['avatar' => app_url('/frontend/assets/images/avatar-3.png'), 'name' => 'Lia Mendes', 'role' => __('home.testimonial_role'), 'text' => __('home.testimonial_3')],
            ];
            foreach ($testimonials as $item):
                $testimonialAvatar = $item['avatar'];
                $testimonialName = $item['name'];
                $testimonialRole = $item['role'];
                $testimonialText = $item['text'];
                require __DIR__ . '/components/testimonial-card.php';
            endforeach;
            ?>
        </div>
    </section>

    <?php require __DIR__ . '/components/subscribe-banner.php'; ?>
<?php require __DIR__ . '/components/footer.php'; ?>
