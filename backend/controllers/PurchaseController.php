<?php

declare(strict_types=1);

final class PurchaseController
{
    private readonly CardPaymentValidator $cardValidator;

    public function __construct(
        private readonly PurchaseService $purchases,
        private readonly CsrfService $csrf,
        private readonly AuthService $auth,
        ?CardPaymentValidator $cardValidator = null,
    ) {
        $this->cardValidator = $cardValidator ?? new CardPaymentValidator();
    }

    public function requireCustomer(): array
    {
        return $this->auth->requireRole('customer');
    }

    /**
     * @return array{ok: bool, message?: string, code?: int, errors?: list<string>}
     */
    public function simulate(array $post): array
    {
        $customer = $this->requireCustomer();

        try {
            $this->csrf->validate(isset($post['csrf_token']) ? (string) $post['csrf_token'] : null);

            $packageId = filter_var($post['package_id'] ?? null, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1],
            ]);

            if ($packageId === false || $packageId === null) {
                return ['ok' => false, 'message' => __('client.purchase_invalid'), 'code' => 422];
            }

            $cardErrors = $this->cardValidator->validate($post);
            if ($cardErrors !== []) {
                return [
                    'ok' => false,
                    'message' => __('card.fix_error'),
                    'errors' => $cardErrors,
                    'code' => 422,
                ];
            }

            $this->purchases->simulate((int) $customer['id'], (int) $packageId);

            return ['ok' => true];
        } catch (InvalidArgumentException $exception) {
            return ['ok' => false, 'message' => $exception->getMessage(), 'code' => 422];
        } catch (RuntimeException $exception) {
            $code = $exception->getCode() === 404 ? 404 : 500;

            return ['ok' => false, 'message' => $exception->getMessage(), 'code' => $code];
        }
    }
}
