<?php

declare(strict_types=1);

/** @var array{page: int, per_page: int, total: int, pages: int, offset: int} $pagination */
$pagination = $pagination ?? null;

if ($pagination === null || (int) $pagination['pages'] <= 1) {
    return;
}

$current = (int) $pagination['page'];
$pages = (int) $pagination['pages'];
$total = (int) $pagination['total'];
$from = $total === 0 ? 0 : ((int) $pagination['offset'] + 1);
$to = min($total, (int) $pagination['offset'] + (int) $pagination['per_page']);
?>
<nav class="mt-6 flex flex-wrap items-center justify-between gap-3" aria-label="<?= e(__('admin.pagination_label')) ?>">
    <p class="text-sm text-dt-muted">
        <?= e(__('admin.pagination_summary', [
            'from' => (string) $from,
            'to' => (string) $to,
            'total' => (string) $total,
        ])) ?>
    </p>
    <div class="flex flex-wrap items-center gap-2">
        <?php if ($current > 1): ?>
            <a class="inline-flex h-8 items-center rounded-full border border-[#ddd] px-3 text-sm font-medium text-dt-ink hover:border-dt-orange hover:text-dt-orange" href="<?= e(pagination_url($current - 1)) ?>">
                <?= e(__('admin.pagination_prev')) ?>
            </a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php if ($i === $current): ?>
                <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-full bg-dt-orange px-2 text-sm font-semibold text-white" aria-current="page"><?= $i ?></span>
            <?php else: ?>
                <a class="inline-flex h-8 min-w-8 items-center justify-center rounded-full border border-[#ddd] px-2 text-sm font-medium text-dt-ink hover:border-dt-orange hover:text-dt-orange" href="<?= e(pagination_url($i)) ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($current < $pages): ?>
            <a class="inline-flex h-8 items-center rounded-full border border-[#ddd] px-3 text-sm font-medium text-dt-ink hover:border-dt-orange hover:text-dt-orange" href="<?= e(pagination_url($current + 1)) ?>">
                <?= e(__('admin.pagination_next')) ?>
            </a>
        <?php endif; ?>
    </div>
</nav>
