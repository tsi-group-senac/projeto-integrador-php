<?php

declare(strict_types=1);

$homeUrl = app_url('/frontend/index.php');
$aboutUrl = app_url('/frontend/pages/about.php');
$toursUrl = app_url('/frontend/pages/tours.php');
$loginUrl = app_url('/frontend/pages/login.php');
$registerUrl = app_url('/frontend/pages/register.php');
?>
</main>
<footer class="border-t border-[#ececec] bg-white">
    <div class="mx-auto grid max-w-site gap-10 px-[40px] py-12 lg:grid-cols-4 lg:px-[160px] lg:py-16">
        <div>
            <?php
            $logoClass = 'h-[60px] w-auto';
            require __DIR__ . '/logo.php';
            ?>
            <p class="mt-4 max-w-[255px] text-[15px] leading-6 text-dt-muted">
                <?= e(__('footer.blurb')) ?>
            </p>
            <div class="mt-5 flex gap-4">
                <?php foreach (['youtube', 'facebook', 'instagram'] as $network): ?>
                    <a class="inline-flex h-[35px] w-[35px] items-center justify-center rounded-full bg-dt-orange text-sm font-bold text-white" href="#" aria-label="<?= e(ucfirst($network)) ?>">
                        <?= e(strtoupper(substr($network, 0, 1))) ?>
                    </a>
                <?php endforeach; ?>
                <a class="inline-flex h-[35px] w-[35px] items-center justify-center rounded-full bg-dt-orange text-sm font-bold text-white" href="#" aria-label="Twitter">X</a>
            </div>
            <p class="mt-6 text-[13px] text-dt-muted"><?= e(__('footer.rights', ['year' => date('Y')])) ?></p>
        </div>

        <div>
            <h2 class="text-[18px] font-bold text-dt-ink"><?= e(__('footer.discover')) ?></h2>
            <ul class="mt-6 space-y-5 text-[16px] text-dt-muted">
                <li><a class="hover:text-dt-orange" href="<?= e($homeUrl) ?>"><?= e(__('nav.home')) ?></a></li>
                <li><a class="hover:text-dt-orange" href="<?= e($aboutUrl) ?>"><?= e(__('nav.about')) ?></a></li>
                <li><a class="hover:text-dt-orange" href="<?= e($toursUrl) ?>"><?= e(__('nav.tours')) ?></a></li>
            </ul>
        </div>

        <div>
            <h2 class="text-[18px] font-bold text-dt-ink"><?= e(__('footer.quick_links')) ?></h2>
            <ul class="mt-6 space-y-5 text-[16px] text-dt-muted">
                <li><a class="hover:text-dt-orange" href="<?= e($homeUrl) ?>#gallery"><?= e(__('footer.gallery')) ?></a></li>
                <li><a class="hover:text-dt-orange" href="<?= e($loginUrl) ?>"><?= e(__('nav.login')) ?></a></li>
                <li><a class="hover:text-dt-orange" href="<?= e($registerUrl) ?>"><?= e(__('nav.register')) ?></a></li>
            </ul>
        </div>

        <div>
            <h2 class="text-[18px] font-bold text-dt-ink"><?= e(__('footer.contact')) ?></h2>
            <ul class="mt-6 space-y-5 text-[16px] text-dt-muted">
                <li><?= e(__('footer.brazil')) ?></li>
                <li><a class="hover:text-dt-orange" href="mailto:contato@destyntrips.com.br">contato@destyntrips.com.br</a></li>
                <li><a class="hover:text-dt-orange" href="https://destyntrips.com.br">destyntrips.com.br</a></li>
            </ul>
        </div>
    </div>
</footer>
<script src="<?= e(app_url('/frontend/js/main.js')) ?>" defer></script>
</body>
</html>
