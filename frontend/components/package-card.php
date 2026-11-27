<?php

declare(strict_types=1);

/** @var array $package */
$detailUrl = app_url('/frontend/pages/package.php?id=' . (int) $package['id']);
$image = !empty($package['image_path'])
    ? (string) $package['image_path']
    : app_url('/frontend/assets/images/rio.jpg');
if (str_starts_with($image, '/frontend/')) {
    $image = app_url($image);
}
$city = (string) ($package['destination_name'] ?? 'Destino');
$title = (string) $package['title'];
$price = number_format((float) $package['price'], 2, ',', '.');
?>
<a href="<?= e($detailUrl) ?>" class="group relative block min-h-[280px] overflow-hidden rounded-[20px]">
    <img class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105" src="<?= e($image) ?>" alt="<?= e($title) ?>">
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/15 to-transparent"></div>
    <div class="absolute inset-x-0 bottom-0 p-5 text-white">
        <p class="font-script text-[40px] leading-none"><?= e($city) ?></p>
        <p class="mt-1 text-[15px] font-semibold"><?= e($title) ?></p>
        <p class="mt-2 text-sm text-white/90">
            <?= e(__('common.days_price', ['price' => $price, 'days' => (string) $package['duration_days']])) ?>
        </p>
    </div>
</a>
