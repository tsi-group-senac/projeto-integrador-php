<?php

declare(strict_types=1);

$user = current_user();
$activeNav = $activeNav ?? 'home';
$homeUrl = app_url('/frontend/index.php');
$aboutUrl = app_url('/frontend/pages/about.php');
$toursUrl = app_url('/frontend/pages/tours.php');
$loginUrl = app_url('/frontend/pages/login.php');
$registerUrl = app_url('/frontend/pages/register.php');

$navClass = static function (string $key, string $active) : string {
    $base = 'text-[15px] font-medium leading-none transition-colors hover:text-dt-orange focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-dt-orange';
    return $key === $active ? $base . ' text-dt-orange' : $base . ' text-dt-ink';
};

$mobileLinkClass = static function (string $key, string $active) : string {
    $base = 'block rounded-lg px-3 py-3 text-[15px] font-medium transition-colors hover:bg-[#fff8ee] hover:text-dt-orange';
    return $key === $active ? $base . ' bg-[#fff8ee] text-dt-orange' : $base . ' text-dt-ink';
};
?>
<a href="#conteudo" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-white focus:px-3 focus:py-2 focus:shadow">
    <?= e(__('nav.skip')) ?>
</a>
<header class="relative z-30 w-full bg-white">
    <div class="mx-auto grid h-[85px] max-w-site grid-cols-[2.75rem_1fr_2.75rem] items-center gap-2 px-4 sm:px-[40px] md:flex md:justify-between md:gap-4 lg:px-[160px]">
        <button
            type="button"
            class="inline-flex h-11 w-11 items-center justify-center rounded-full text-dt-ink hover:bg-[#fff8ee] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-dt-orange md:hidden"
            data-mobile-nav-toggle
            aria-controls="mobile-nav"
            aria-expanded="false"
            aria-label="<?= e(__('nav.menu')) ?>"
        >
            <span data-icon-open class="inline-flex" aria-hidden="true">
                <i data-lucide="menu" class="h-6 w-6"></i>
            </span>
            <span data-icon-close class="hidden inline-flex" aria-hidden="true">
                <i data-lucide="x" class="h-6 w-6"></i>
            </span>
        </button>

        <div class="flex justify-center md:justify-start">
            <?php
            $logoClass = 'h-[56px] w-auto md:h-[70px]';
            require __DIR__ . '/logo.php';
            ?>
        </div>

        <span class="md:hidden" aria-hidden="true"></span>

        <nav aria-label="Principal" class="hidden items-center gap-6 md:flex lg:gap-8">
            <a class="<?= e($navClass('home', $activeNav)) ?>" href="<?= e($homeUrl) ?>"><?= e(__('nav.home')) ?></a>
            <a class="<?= e($navClass('about', $activeNav)) ?>" href="<?= e($aboutUrl) ?>"><?= e(__('nav.about')) ?></a>
            <a class="<?= e($navClass('tours', $activeNav)) ?>" href="<?= e($toursUrl) ?>"><?= e(__('nav.tours')) ?></a>
            <?php if ($user === null): ?>
                <a class="<?= e($navClass('login', $activeNav)) ?>" href="<?= e($loginUrl) ?>"><?= e(__('nav.login')) ?></a>
                <a class="inline-flex h-8 items-center justify-center rounded-full bg-dt-orange px-4 text-sm font-semibold text-white hover:brightness-95 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-dt-orange" href="<?= e($registerUrl) ?>">
                    <?= e(__('nav.register')) ?>
                </a>
            <?php else: ?>
                <?php if (($user['role'] ?? '') === 'admin'): ?>
                    <a class="<?= e($navClass('admin', $activeNav)) ?>" href="<?= e(app_url('/frontend/pages/admin/index.php')) ?>"><?= e(__('nav.admin')) ?></a>
                <?php else: ?>
                    <a class="<?= e($navClass('client', $activeNav)) ?>" href="<?= e(app_url('/frontend/pages/client/dashboard.php')) ?>"><?= e(__('nav.client')) ?></a>
                <?php endif; ?>
                <form method="post" action="<?= e(app_url('/frontend/pages/logout.php')) ?>" class="inline">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfService->token()) ?>">
                    <button type="submit" class="inline-flex h-8 items-center justify-center rounded-full border border-dt-orange px-4 text-sm font-semibold text-dt-orange hover:bg-dt-orange hover:text-white">
                        <?= e(__('nav.logout')) ?>
                    </button>
                </form>
            <?php endif; ?>
            <?php require __DIR__ . '/lang-switcher.php'; ?>
        </nav>
    </div>

    <nav
        id="mobile-nav"
        data-mobile-nav
        aria-label="<?= e(__('nav.menu')) ?>"
        class="hidden border-t border-[#ececec] bg-white px-4 py-3 shadow-sm md:hidden"
    >
        <div class="mx-auto flex max-w-site flex-col gap-1">
            <a class="<?= e($mobileLinkClass('home', $activeNav)) ?>" href="<?= e($homeUrl) ?>"><?= e(__('nav.home')) ?></a>
            <a class="<?= e($mobileLinkClass('about', $activeNav)) ?>" href="<?= e($aboutUrl) ?>"><?= e(__('nav.about')) ?></a>
            <a class="<?= e($mobileLinkClass('tours', $activeNav)) ?>" href="<?= e($toursUrl) ?>"><?= e(__('nav.tours')) ?></a>
            <?php if ($user === null): ?>
                <a class="<?= e($mobileLinkClass('login', $activeNav)) ?>" href="<?= e($loginUrl) ?>"><?= e(__('nav.login')) ?></a>
                <a class="mt-2 inline-flex h-10 items-center justify-center rounded-full bg-dt-orange px-4 text-sm font-semibold text-white" href="<?= e($registerUrl) ?>">
                    <?= e(__('nav.register')) ?>
                </a>
            <?php else: ?>
                <?php if (($user['role'] ?? '') === 'admin'): ?>
                    <a class="<?= e($mobileLinkClass('admin', $activeNav)) ?>" href="<?= e(app_url('/frontend/pages/admin/index.php')) ?>"><?= e(__('nav.admin')) ?></a>
                <?php else: ?>
                    <a class="<?= e($mobileLinkClass('client', $activeNav)) ?>" href="<?= e(app_url('/frontend/pages/client/dashboard.php')) ?>"><?= e(__('nav.client')) ?></a>
                <?php endif; ?>
                <form method="post" action="<?= e(app_url('/frontend/pages/logout.php')) ?>" class="mt-2">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfService->token()) ?>">
                    <button type="submit" class="inline-flex h-10 w-full items-center justify-center rounded-full border border-dt-orange px-4 text-sm font-semibold text-dt-orange">
                        <?= e(__('nav.logout')) ?>
                    </button>
                </form>
            <?php endif; ?>
            <div class="mt-3 border-t border-[#f0f0f0] pt-3">
                <?php require __DIR__ . '/lang-switcher.php'; ?>
            </div>
        </div>
    </nav>
</header>
