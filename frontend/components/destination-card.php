<?php

declare(strict_types=1);

/** @var string $destinationImage */
/** @var string $destinationCity */
/** @var string $destinationCountry */
$destinationHref = $destinationHref ?? '#';
$destinationClass = $destinationClass ?? 'min-h-[220px]';
?>
<a href="<?= e($destinationHref) ?>" class="group relative block overflow-hidden rounded-[20px] <?= e($destinationClass) ?>">
    <img class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105" src="<?= e($destinationImage) ?>" alt="<?= e($destinationCity . ', ' . $destinationCountry) ?>">
    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>
    <div class="absolute inset-x-0 bottom-0 p-5 text-center text-white">
        <p class="font-script text-[42px] leading-none"><?= e($destinationCity) ?></p>
        <p class="mt-1 text-[14px] font-semibold tracking-[0.18em]"><?= e(strtoupper($destinationCountry)) ?></p>
    </div>
</a>
