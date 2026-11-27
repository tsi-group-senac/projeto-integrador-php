<?php

declare(strict_types=1);

/** @var string $serviceIcon */
/** @var string $serviceTitle */
/** @var string $serviceText */
?>
<article class="rounded-[20px] bg-white px-6 py-8 text-center shadow-[0_12px_30px_rgba(11,39,39,0.08)]">
    <div class="mx-auto flex h-[50px] w-[50px] items-center justify-center rounded-full bg-dt-orange/15">
        <img class="h-8 w-8 object-contain" src="<?= e($serviceIcon) ?>" alt="" width="32" height="32">
    </div>
    <h3 class="mt-5 text-[18px] font-semibold text-dt-ink"><?= e($serviceTitle) ?></h3>
    <p class="mt-3 text-[14px] leading-6 text-dt-muted"><?= e($serviceText) ?></p>
</article>
