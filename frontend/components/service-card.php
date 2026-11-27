<?php

declare(strict_types=1);

/** @var string $serviceIcon Lucide icon name (data-lucide) */
/** @var string $serviceTitle */
/** @var string $serviceText */
?>
<article class="rounded-[20px] bg-white px-6 py-8 text-center shadow-[0_12px_30px_rgba(11,39,39,0.08)]">
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#fff4e5] ring-1 ring-[#faa935]/30">
        <i data-lucide="<?= e($serviceIcon) ?>" class="h-7 w-7 text-dt-orange" aria-hidden="true"></i>
    </div>
    <h3 class="mt-5 text-[18px] font-semibold text-dt-ink"><?= e($serviceTitle) ?></h3>
    <p class="mt-3 text-[14px] leading-6 text-dt-muted"><?= e($serviceText) ?></p>
</article>
