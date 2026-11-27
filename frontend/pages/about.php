<?php

declare(strict_types=1);

require_once __DIR__ . '/../../backend/bootstrap.php';

$activeNav = 'about';
$pageTitle = __('meta.about_title');
require __DIR__ . '/../components/head.php';
require __DIR__ . '/../components/header.php';
?>
<main id="conteudo">
    <section class="relative h-[360px] overflow-hidden sm:h-[420px]">
        <img class="absolute inset-0 h-full w-full object-cover" src="<?= e(app_url('/frontend/assets/images/hero-travel-2.jpg')) ?>" alt="<?= e(__('meta.about_title')) ?>">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 mx-auto flex h-full max-w-content flex-col items-center justify-center px-4 text-center">
            <h1 class="text-[42px] font-semibold text-white sm:text-[56px]">
                <?= e(__('about.hero')) ?> <span class="text-dt-orange"><?= e(__('about.hero_accent')) ?></span>
            </h1>
            <p class="mt-4 max-w-2xl text-[15px] leading-7 text-white/90">
                <?= e(__('about.hero_lead')) ?>
            </p>
        </div>
    </section>

    <section class="mx-auto grid max-w-content items-start gap-10 px-4 py-16 lg:grid-cols-2 lg:px-0">
        <div>
            <h2 class="text-[32px] font-semibold text-dt-ink sm:text-[40px]">
                <?= e(__('about.who')) ?> <span class="text-dt-orange"><?= e(__('about.who_accent')) ?></span>
            </h2>
            <p class="mt-5 whitespace-pre-line text-[15px] leading-8 text-dt-muted">
                <?= e(__('about.who_text')) ?>
            </p>
        </div>
        <div>
            <img class="w-full rounded-[20px] object-cover shadow-lg" src="<?= e(app_url('/frontend/assets/images/hero-travel-1.jpg')) ?>" alt="<?= e(__('about.who')) ?>">
        </div>
    </section>

    <section class="bg-[#faf7f2]">
        <div class="mx-auto grid max-w-content items-start gap-10 px-4 py-16 lg:grid-cols-2 lg:px-0">
            <div class="order-2 lg:order-1">
                <img class="w-full rounded-[20px] object-cover shadow-lg" src="<?= e(app_url('/frontend/assets/images/rio.jpg')) ?>" alt="<?= e(__('about.mission')) ?>">
            </div>
            <div class="order-1 lg:order-2">
                <h2 class="text-[32px] font-semibold text-dt-ink sm:text-[40px]">
                    <?= e(__('about.mission')) ?> <span class="text-dt-orange"><?= e(__('about.mission_accent')) ?></span>
                </h2>
                <p class="mt-5 whitespace-pre-line text-[15px] leading-8 text-dt-muted">
                    <?= e(__('about.mission_text')) ?>
                </p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-content px-4 py-16 lg:px-0">
        <h2 class="text-center text-[28px] font-semibold text-dt-ink sm:text-[34px]">
            <?= e(__('about.values_title')) ?>
        </h2>
        <p class="mx-auto mt-3 max-w-2xl text-center text-[15px] leading-7 text-dt-muted">
            <?= e(__('about.values_lead')) ?>
        </p>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            <?php
            $values = [
                ['title' => __('about.value_1_title'), 'text' => __('about.value_1_text')],
                ['title' => __('about.value_2_title'), 'text' => __('about.value_2_text')],
                ['title' => __('about.value_3_title'), 'text' => __('about.value_3_text')],
            ];
            foreach ($values as $value):
                ?>
                <article class="rounded-2xl border border-[#eee] bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-dt-ink"><?= e($value['title']) ?></h3>
                    <p class="mt-3 text-[14px] leading-7 text-dt-muted"><?= e($value['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="mx-auto max-w-content px-4 pb-16 lg:px-0">
        <?php
        $eyebrow = __('about.services_eyebrow');
        $titlePrefix = __('about.services_title');
        $titleAccent = '';
        $align = 'center';
        require __DIR__ . '/../components/section-heading.php';
        ?>
        <p class="mx-auto mt-3 max-w-2xl text-center text-[15px] leading-7 text-dt-muted">
            <?= e(__('about.services_lead')) ?>
        </p>
        <div class="mt-10 grid gap-6 sm:grid-cols-3">
            <?php
            $services = [
                ['icon' => 'cloud-sun', 'title' => __('home.service_weather_title'), 'text' => __('about.service_weather_text')],
                ['icon' => 'map-pinned', 'title' => __('home.service_guide_title'), 'text' => __('about.service_guide_text')],
                ['icon' => 'sliders-horizontal', 'title' => __('home.service_custom_title'), 'text' => __('about.service_custom_text')],
            ];
            foreach ($services as $service):
                $serviceIcon = $service['icon'];
                $serviceTitle = $service['title'];
                $serviceText = $service['text'];
                require __DIR__ . '/../components/service-card.php';
            endforeach;
            ?>
        </div>
    </section>

    <section class="bg-dt-sky">
        <div class="mx-auto max-w-content px-4 py-14 text-center lg:px-0">
            <h2 class="text-[28px] font-semibold text-dt-ink sm:text-[34px]"><?= e(__('about.promise_title')) ?></h2>
            <p class="mx-auto mt-4 max-w-3xl whitespace-pre-line text-[15px] leading-8 text-dt-muted">
                <?= e(__('about.promise_text')) ?>
            </p>
            <a class="mt-8 inline-flex h-10 items-center rounded-full bg-dt-orange px-5 text-sm font-semibold text-white hover:brightness-95" href="<?= e(app_url('/frontend/pages/tours.php')) ?>">
                <?= e(__('nav.tours')) ?>
            </a>
        </div>
    </section>

    <?php require __DIR__ . '/../components/subscribe-banner.php'; ?>
<?php require __DIR__ . '/../components/footer.php'; ?>
