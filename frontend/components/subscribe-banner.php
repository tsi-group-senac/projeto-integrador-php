<?php

declare(strict_types=1);
?>
<section class="bg-dt-sky">
    <div class="mx-auto grid max-w-content items-end gap-8 px-4 pt-12 lg:grid-cols-2 lg:px-0">
        <div class="pb-12">
            <h2 class="max-w-md text-[34px] font-semibold leading-tight text-dt-ink sm:text-[40px]">
                <?= e(__('subscribe.title')) ?>
            </h2>
            <form class="mt-6 flex max-w-xl overflow-hidden rounded-[8px] bg-white p-1 shadow-sm" action="#" method="post" onsubmit="return false;">
                <label class="sr-only" for="subscribe-email"><?= e(__('subscribe.email_ph')) ?></label>
                <input id="subscribe-email" class="min-w-0 flex-1 border-0 px-4 py-3 text-[16px] text-dt-muted outline-none" type="email" name="email" placeholder="<?= e(__('subscribe.email_ph')) ?>" autocomplete="email">
                <button class="rounded-md bg-dt-orange px-4 py-2 text-sm font-medium text-white" type="submit"><?= e(__('subscribe.button')) ?></button>
            </form>
            <p class="mt-4 max-w-md text-[14px] leading-6 text-dt-muted">
                <?= e(__('subscribe.hint')) ?>
            </p>
        </div>
        <div class="flex justify-center lg:justify-end">
            <img class="max-h-[360px] object-contain" src="<?= e(app_url('/frontend/assets/images/subscribe-tourist.png')) ?>" alt="<?= e(__('subscribe.alt')) ?>">
        </div>
    </div>
</section>
