<?php

declare(strict_types=1);

final class CsrfService
{
    private const SESSION_KEY = '_csrf_token';

    public function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public function validate(?string $token): void
    {
        $sessionToken = $_SESSION[self::SESSION_KEY] ?? '';

        if ($token === null || $sessionToken === '' || !hash_equals($sessionToken, $token)) {
            throw new InvalidArgumentException('A sessão expirou. Envie o formulário novamente.');
        }
    }
}
