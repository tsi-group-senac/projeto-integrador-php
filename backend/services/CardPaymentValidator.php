<?php

declare(strict_types=1);

final class CardPaymentValidator
{
    /**
     * Validates demo card fields by format/size only (no Luhn / no real charge).
     *
     * @param array<string, mixed> $post
     * @return list<string>
     */
    public function validate(array $post): array
    {
        $errors = [];

        $holder = trim((string) ($post['card_holder'] ?? ''));
        $number = preg_replace('/\D+/', '', (string) ($post['card_number'] ?? '')) ?? '';
        $expiry = trim((string) ($post['card_expiry'] ?? ''));
        $cvv = preg_replace('/\D+/', '', (string) ($post['card_cvv'] ?? '')) ?? '';

        if (mb_strlen($holder) < 3 || mb_strlen($holder) > 80) {
            $errors[] = __('card.holder_invalid');
        }

        $numberLen = strlen($number);
        if ($numberLen < 13 || $numberLen > 19) {
            $errors[] = __('card.number_invalid');
        }

        if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $expiry, $matches)) {
            $errors[] = __('card.expiry_invalid');
        } else {
            $month = (int) $matches[1];
            $year = 2000 + (int) $matches[2];
            $lastDay = (int) date('t', mktime(0, 0, 0, $month, 1, $year));
            $expiryTs = mktime(23, 59, 59, $month, $lastDay, $year);
            if ($expiryTs === false || $expiryTs < time()) {
                $errors[] = __('card.expiry_past');
            }
        }

        $cvvLen = strlen($cvv);
        if ($cvvLen < 3 || $cvvLen > 4) {
            $errors[] = __('card.cvv_invalid');
        }

        return $errors;
    }
}
