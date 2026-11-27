<?php

declare(strict_types=1);

$logoClass = $logoClass ?? 'h-[70px] w-auto';
$logoHref = $logoHref ?? app_url('/frontend/index.php');
$logoSrc = app_url('/frontend/assets/logos/destyn-trips-logo.svg');
?>
<a class="inline-flex shrink-0 items-center" href="<?= e($logoHref) ?>" aria-label="<?= e(__('nav.logo_home')) ?>">
    <img class="<?= e($logoClass) ?>" src="<?= e($logoSrc) ?>" width="220" height="70" alt="Destyn Trips">
</a>
