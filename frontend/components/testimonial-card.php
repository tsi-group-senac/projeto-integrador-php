<?php

declare(strict_types=1);

/** @var string $testimonialAvatar */
/** @var string $testimonialName */
/** @var string $testimonialRole */
/** @var string $testimonialText */
?>
<article class="rounded-[16px] bg-white p-6 shadow-[0_10px_30px_rgba(11,39,39,0.08)]">
    <p class="text-[15px] leading-7 text-dt-muted"><?= e($testimonialText) ?></p>
    <div class="mt-6 flex items-center gap-3">
        <img class="h-12 w-12 rounded-full object-cover" src="<?= e($testimonialAvatar) ?>" alt="<?= e($testimonialName) ?>">
        <div>
            <p class="text-[16px] font-semibold text-dt-ink"><?= e($testimonialName) ?></p>
            <p class="text-[14px] text-dt-muted"><?= e($testimonialRole) ?></p>
        </div>
    </div>
</article>
