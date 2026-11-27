<?php

declare(strict_types=1);

$stats = [
    ['value' => '12k+', 'label' => __('experience.trips')],
    ['value' => '2k+', 'label' => __('experience.clients')],
    ['value' => '15', 'label' => __('experience.years')],
];
?>
<section class="mx-auto grid max-w-content items-center gap-10 px-4 py-16 lg:grid-cols-2 lg:px-0">
    <div>
        <h2 class="max-w-md text-[36px] font-semibold leading-tight text-dt-ink sm:text-[40px]">
            <?= e(__('experience.title')) ?>
        </h2>
        <p class="mt-4 max-w-md text-[15px] leading-7 text-dt-muted">
            <?= e(__('experience.text')) ?>
        </p>
        <div class="mt-8 flex flex-wrap gap-4">
            <?php foreach ($stats as $stat): ?>
                <div class="min-w-[110px] rounded-[12px] bg-dt-orange px-4 py-4 text-center text-white">
                    <p class="text-[28px] font-bold leading-none"><?= e($stat['value']) ?></p>
                    <p class="mt-2 text-[13px] font-medium"><?= e($stat['label']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="relative mx-auto flex h-[360px] w-full max-w-[420px] items-center justify-center">
        <div class="absolute inset-8 rounded-full border-[18px] border-dt-orange/80"></div>
        <img class="relative z-10 max-h-[340px] object-contain" src="<?= e(app_url('/frontend/assets/images/experience.png')) ?>" alt="<?= e(__('experience.alt')) ?>">
    </div>
</section>
