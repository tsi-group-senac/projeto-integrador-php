<?php

declare(strict_types=1);

require_once __DIR__ . '/../../backend/bootstrap.php';

$formErrors = [];
$email = strtolower(trim((string) ($_POST['email'] ?? '')));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $csrfService->validate(isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : null);
        $password = (string) ($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            throw new InvalidArgumentException(__('login.invalid'));
        }

        $user = $authService->login($email, $password);
        redirect($user['role'] === 'admin'
            ? app_url('/frontend/pages/admin/index.php')
            : app_url('/frontend/pages/client/dashboard.php'));
    } catch (InvalidArgumentException $exception) {
        $formErrors[] = $exception->getMessage();
    } catch (PDOException $exception) {
        error_log('Falha ao autenticar usuário: ' . $exception->getMessage());
        $formErrors[] = __('login.error');
    }
}

if (isset($_GET['registered'])) {
    flash_set('success', __('login.registered_flash'));
}
if (isset($_GET['logged_out'])) {
    flash_set('success', __('login.logged_out_flash'));
}

$activeNav = 'login';
$pageTitle = __('meta.login_title');
require __DIR__ . '/../components/head.php';
require __DIR__ . '/../components/header.php';
?>
<main id="conteudo" class="mx-auto max-w-site px-4 py-8 lg:px-[160px]">
    <div class="grid overflow-hidden rounded-[20px] lg:grid-cols-2">
        <div class="min-h-[420px]">
            <img class="h-full w-full rounded-[20px] object-cover lg:rounded-none lg:rounded-l-[20px]" src="<?= e(app_url('/frontend/assets/images/hero-side-1.png')) ?>" alt="<?= e(__('login.alt')) ?>">
        </div>
        <div class="relative flex flex-col justify-center bg-white px-6 py-10 sm:px-12">
            <div class="mb-2 text-dt-orange" aria-hidden="true">✈····</div>
            <h1 class="text-[48px] font-semibold leading-none text-dt-orange"><?= e(__('login.welcome')) ?></h1>
            <p class="mt-3 text-[16px] text-dt-muted"><?= e(__('login.with_email')) ?></p>

            <?php require __DIR__ . '/../components/messages.php'; ?>

            <form method="post" class="mt-8 space-y-4" novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($csrfService->token()) ?>">
                <div>
                    <label class="sr-only" for="email"><?= e(__('login.email_ph')) ?></label>
                    <input class="h-10 w-full rounded-lg border border-[#f3d7a8] px-3 text-sm outline-none focus:border-dt-orange" id="email" name="email" type="email" required autocomplete="email" placeholder="<?= e(__('login.email_ph')) ?>" value="<?= e($email) ?>">
                </div>
                <div>
                    <label class="sr-only" for="password"><?= e(__('login.password_ph')) ?></label>
                    <input class="h-10 w-full rounded-lg border border-[#f3d7a8] px-3 text-sm outline-none focus:border-dt-orange" id="password" name="password" type="password" required autocomplete="current-password" placeholder="<?= e(__('login.password_ph')) ?>">
                </div>
                <div class="text-right">
                    <span class="text-xs text-dt-orange"><?= e(__('login.forgot')) ?></span>
                </div>
                <button class="h-10 w-full rounded-lg bg-dt-orange text-sm font-semibold uppercase tracking-wide text-white hover:brightness-95" type="submit">
                    <?= e(__('login.submit')) ?>
                </button>
            </form>

            <p class="mt-8 text-center text-[14px] text-dt-muted">
                <?= e(__('login.no_account')) ?>
                <a class="font-semibold text-dt-orange hover:underline" href="<?= e(app_url('/frontend/pages/register.php')) ?>"><?= e(__('login.register_now')) ?></a>
            </p>
            <img class="pointer-events-none absolute bottom-2 right-4 hidden w-40 opacity-90 lg:block" src="<?= e(app_url('/frontend/assets/images/world-decor.png')) ?>" alt="">
        </div>
    </div>
<?php require __DIR__ . '/../components/footer.php'; ?>
