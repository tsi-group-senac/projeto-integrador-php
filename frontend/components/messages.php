<?php

declare(strict_types=1);

$flash = flash_get();
$formErrors = $formErrors ?? [];
$fieldErrors = $fieldErrors ?? [];
?>
<?php if ($flash !== null): ?>
    <?php $isSuccess = ($flash['type'] ?? '') === 'success'; ?>
    <div class="mb-4 rounded-[6px] border px-4 py-3 <?= $isSuccess ? 'border-emerald-300 bg-emerald-50 text-emerald-900' : 'border-red-300 bg-red-50 text-red-900' ?>" role="<?= $isSuccess ? 'status' : 'alert' ?>">
        <?= e((string) ($flash['message'] ?? '')) ?>
    </div>
<?php endif; ?>
<?php if ($formErrors !== []): ?>
    <div class="mb-4 rounded-[6px] border border-red-300 bg-red-50 px-4 py-3 text-red-900" role="alert">
        <ul class="list-disc space-y-1 pl-5">
            <?php foreach ($formErrors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if ($fieldErrors !== []): ?>
    <div class="mb-4 rounded-[6px] border border-red-300 bg-red-50 px-4 py-3 text-red-900" role="alert">
        <ul class="list-disc space-y-1 pl-5">
            <?php foreach ($fieldErrors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
