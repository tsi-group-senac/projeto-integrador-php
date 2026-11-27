<?php

declare(strict_types=1);

require_once __DIR__ . '/../../backend/bootstrap.php';

$formErrors = [];
$old = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'mobile' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['first_name'] = trim((string) ($_POST['first_name'] ?? ''));
    $old['last_name'] = trim((string) ($_POST['last_name'] ?? ''));
    $old['email'] = strtolower(trim((string) ($_POST['email'] ?? '')));
    $old['mobile'] = trim((string) ($_POST['mobile'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $name = trim($old['first_name'] . ' ' . $old['last_name']);

    try {
        $csrfService->validate(isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : null);

        if (mb_strlen($old['first_name']) < 1 || mb_strlen($name) < 2 || mb_strlen($name) > 120) {
            $formErrors[] = __('register.name_invalid');
        }
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL) || strlen($old['email']) > 180) {
            $formErrors[] = __('register.email_invalid');
        }
        if (strlen($password) < 8) {
            $formErrors[] = __('register.password_short');
        }
        if ($formErrors === [] && $userModel->findByEmail($old['email']) !== null) {
            $formErrors[] = __('register.email_taken');
        }

        if ($formErrors === []) {
            $userModel->createCustomer($name, $old['email'], $password);
            redirect(app_url('/frontend/pages/login.php?registered=1'));
        }
    } catch (InvalidArgumentException $exception) {
        $formErrors[] = $exception->getMessage();
    } catch (PDOException $exception) {
        error_log('Falha ao cadastrar cliente: ' . $exception->getMessage());
        $formErrors[] = __('register.error');
    }
}

$activeNav = 'register';
$pageTitle = __('meta.register_title');
require __DIR__ . '/../components/head.php';
require __DIR__ . '/../components/header.php';
?>
<main id="conteudo" class="mx-auto max-w-site px-4 py-8 lg:px-[160px]">
    <div class="grid overflow-hidden rounded-[20px] lg:grid-cols-2">
        <div class="min-h-[480px]">
            <img class="h-full w-full rounded-[20px] object-cover lg:rounded-none lg:rounded-l-[20px]" src="<?= e(app_url('/frontend/assets/images/hero-side-1.png')) ?>" alt="<?= e(__('register.alt')) ?>">
        </div>
        <div class="relative bg-white px-6 py-10 sm:px-12">
            <div class="mb-2 text-dt-orange" aria-hidden="true">✈····</div>
            <h1 class="font-serif text-[34px] font-semibold uppercase tracking-wide text-dt-orange sm:text-[40px]"><?= e(__('register.title')) ?></h1>
            <p class="mt-3 text-[13px] text-dt-muted">
                <?= e(__('register.terms')) ?>
                <span class="text-dt-orange"><?= e(__('register.privacy')) ?></span> <?= e(__('register.and')) ?>
                <span class="text-dt-orange"><?= e(__('register.tos')) ?></span>.
            </p>

            <?php require __DIR__ . '/../components/messages.php'; ?>

            <form method="post" class="mt-6 space-y-4" novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($csrfService->token()) ?>">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="sr-only" for="first_name"><?= e(__('register.first_name')) ?></label>
                        <input class="h-10 w-full rounded-lg border border-[#f3d7a8] px-3 text-sm outline-none focus:border-dt-orange" id="first_name" name="first_name" required maxlength="60" placeholder="<?= e(__('register.first_name')) ?>" value="<?= e($old['first_name']) ?>">
                    </div>
                    <div>
                        <label class="sr-only" for="last_name"><?= e(__('register.last_name')) ?></label>
                        <input class="h-10 w-full rounded-lg border border-[#f3d7a8] px-3 text-sm outline-none focus:border-dt-orange" id="last_name" name="last_name" required maxlength="60" placeholder="<?= e(__('register.last_name')) ?>" value="<?= e($old['last_name']) ?>">
                    </div>
                </div>
                <div>
                    <label class="sr-only" for="email"><?= e(__('register.email')) ?></label>
                    <input class="h-10 w-full rounded-lg border border-[#f3d7a8] px-3 text-sm outline-none focus:border-dt-orange" id="email" name="email" type="email" required maxlength="180" placeholder="<?= e(__('register.email')) ?>" value="<?= e($old['email']) ?>">
                </div>
                <div>
                    <label class="sr-only" for="password"><?= e(__('register.password')) ?></label>
                    <input class="h-10 w-full rounded-lg border border-[#f3d7a8] px-3 text-sm outline-none focus:border-dt-orange" id="password" name="password" type="password" required minlength="8" placeholder="<?= e(__('register.password')) ?>">
                </div>
                <div>
                    <label class="sr-only" for="mobile"><?= e(__('register.mobile')) ?></label>
                    <input class="h-10 w-full rounded-lg border border-[#f3d7a8] px-3 text-sm outline-none focus:border-dt-orange" id="mobile" name="mobile" type="tel" placeholder="<?= e(__('register.mobile')) ?>" value="<?= e($old['mobile']) ?>">
                </div>
                <button class="h-10 w-full rounded-lg bg-dt-orange text-sm font-semibold uppercase tracking-wide text-white hover:brightness-95" type="submit">
                    <?= e(__('register.submit')) ?>
                </button>
            </form>

            <p class="mt-8 text-center text-[14px] text-dt-muted">
                <?= e(__('register.has_account')) ?>
                <a class="font-semibold text-dt-orange hover:underline" href="<?= e(app_url('/frontend/pages/login.php')) ?>"><?= e(__('register.login')) ?></a>
            </p>
            <img class="pointer-events-none absolute bottom-2 right-4 hidden w-40 opacity-90 lg:block" src="<?= e(app_url('/frontend/assets/images/world-decor.png')) ?>" alt="">
        </div>
    </div>
<?php require __DIR__ . '/../components/footer.php'; ?>
