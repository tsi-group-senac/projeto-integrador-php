<?php

declare(strict_types=1);

$current = locale();
$labels = [
    'pt_BR' => 'PT',
    'en' => 'EN',
    'es' => 'ES',
];
$currentPath = (string) ($_SERVER['REQUEST_URI'] ?? app_url('/frontend/index.php'));
?>
<div class="flex items-center gap-1" role="navigation" aria-label="<?= e(__('nav.language')) ?>">
    <?php foreach ($labels as $code => $label): ?>
        <?php
        $href = app_url('/frontend/pages/locale.php') . '?lang=' . rawurlencode($code) . '&redirect=' . rawurlencode($currentPath);
        $active = $code === $current;
        ?>
        <a
            class="inline-flex h-8 min-w-8 items-center justify-center rounded-full px-2 text-[12px] font-semibold <?= $active ? 'bg-dt-orange text-white' : 'border border-[#e5e5e5] text-dt-ink hover:border-dt-orange hover:text-dt-orange' ?>"
            href="<?= e($href) ?>"
            hreflang="<?= e(str_replace('_', '-', $code)) ?>"
            <?= $active ? 'aria-current="true"' : '' ?>
        >
            <?= e($label) ?>
        </a>
    <?php endforeach; ?>
</div>
