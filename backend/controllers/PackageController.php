<?php

declare(strict_types=1);

final class PackageController
{
    public function __construct(
        private readonly PackageService $packages,
        private readonly CsrfService $csrf,
        private readonly AuthService $auth,
    ) {
    }

    public function requireAdmin(): array
    {
        return $this->auth->requireRole('admin');
    }

    /**
     * @return array{ok: bool, package?: array, errors?: array<string, string>, message?: string}
     */
    public function save(?int $id, array $post): array
    {
        $admin = $this->requireAdmin();

        try {
            $this->csrf->validate(isset($post['csrf_token']) ? (string) $post['csrf_token'] : null);

            if ($id === null) {
                $package = $this->packages->create($post, (int) $admin['id']);
            } else {
                $package = $this->packages->update($id, $post, (int) $admin['id']);
            }

            return ['ok' => true, 'package' => $package];
        } catch (InvalidArgumentException $exception) {
            $decoded = json_decode($exception->getMessage(), true);

            if (is_array($decoded)) {
                return ['ok' => false, 'errors' => $decoded];
            }

            return ['ok' => false, 'errors' => ['_form' => $exception->getMessage()]];
        } catch (RuntimeException $exception) {
            if ($exception->getCode() === 404) {
                return ['ok' => false, 'message' => $exception->getMessage()];
            }

            throw $exception;
        }
    }

    public function delete(int $id, array $post): array
    {
        $admin = $this->requireAdmin();

        try {
            $this->csrf->validate(isset($post['csrf_token']) ? (string) $post['csrf_token'] : null);
            $this->packages->delete($id, (int) $admin['id']);

            return ['ok' => true];
        } catch (InvalidArgumentException $exception) {
            return ['ok' => false, 'message' => $exception->getMessage()];
        } catch (RuntimeException $exception) {
            return ['ok' => false, 'message' => $exception->getMessage()];
        }
    }
}
