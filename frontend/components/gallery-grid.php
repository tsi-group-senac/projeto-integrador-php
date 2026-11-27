<?php

declare(strict_types=1);

$galleryImages = [
    app_url('/frontend/assets/images/gallery-1.png'),
    app_url('/frontend/assets/images/gallery-2.png'),
    app_url('/frontend/assets/images/gallery-3.png'),
    app_url('/frontend/assets/images/gallery-4.png'),
    app_url('/frontend/assets/images/gallery-5.png'),
    app_url('/frontend/assets/images/gallery-6.png'),
    app_url('/frontend/assets/images/gallery-wide.png'),
    app_url('/frontend/assets/images/tour-7.png'),
];
?>
<section id="gallery" class="mx-auto max-w-content px-4 py-16 lg:px-0">
    <?php
    $eyebrow = __('gallery.eyebrow');
    $titlePrefix = __('gallery.title');
    $titleAccent = '';
    require __DIR__ . '/section-heading.php';
    ?>
    <div class="mt-10 columns-2 gap-4 space-y-4 md:columns-3 lg:columns-4">
        <?php foreach ($galleryImages as $index => $src): ?>
            <img class="mb-4 w-full break-inside-avoid rounded-[16px] object-cover" src="<?= e($src) ?>" alt="<?= e(__('gallery.alt', ['n' => $index + 1])) ?>">
        <?php endforeach; ?>
    </div>
</section>
