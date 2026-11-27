<?php

declare(strict_types=1);

$eyebrow = $eyebrow ?? '';
$titlePrefix = $titlePrefix ?? '';
$titleAccent = $titleAccent ?? '';
$align = $align ?? 'center';
$wrapperClass = $align === 'left' ? 'text-left' : 'text-center';
?>
<div class="<?= e($wrapperClass) ?>">
    <?php if ($eyebrow !== ''): ?>
        <p class="font-script text-[40px] leading-none text-dt-orange"><?= e($eyebrow) ?></p>
    <?php endif; ?>
    <h2 class="mt-1 text-[36px] font-semibold leading-tight text-dt-ink sm:text-[40px]">
        <?= e($titlePrefix) ?><?php if ($titleAccent !== ''): ?> <span class="text-dt-orange"><?= e($titleAccent) ?></span><?php endif; ?>
    </h2>
</div>
