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
?>
<a href="#conteudo" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-white focus:px-3 focus:py-2 focus:shadow">
    <?= e(__('nav.skip')) ?>
</a>
<header class="relative z-30 w-full bg-white">
    <div class="mx-auto flex h-[85px] max-w-site items-center justify-between gap-4 px-[40px] lg:px-[160px]">
        <?php require __DIR__ . '/logo.php'; ?>

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

        <div class="flex items-center gap-2 md:hidden">
            <?php require __DIR__ . '/lang-switcher.php'; ?>
            <a class="inline-flex h-8 items-center rounded-full bg-dt-orange px-3 text-xs font-semibold text-white" href="<?= e($loginUrl) ?>"><?= e(__('nav.login')) ?></a>
            <a class="inline-flex h-8 items-center rounded-full border border-dt-orange px-3 text-xs font-semibold text-dt-orange" href="<?= e($toursUrl) ?>"><?= e(__('nav.tours')) ?></a>
        </div>
    </div>
</header>
