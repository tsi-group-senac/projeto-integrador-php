<?php

declare(strict_types=1);

final class I18n
{
    public const LOCALES = ['pt_BR', 'en', 'es'];
    public const DEFAULT_LOCALE = 'pt_BR';

    private static string $locale = self::DEFAULT_LOCALE;

    /** @var array<string, string> */
    private static array $messages = [];

    public static function boot(): void
    {
        $requested = null;

        if (isset($_GET['lang']) && is_string($_GET['lang'])) {
            $requested = self::normalize($_GET['lang']);
        } elseif (isset($_SESSION['_locale']) && is_string($_SESSION['_locale'])) {
            $requested = self::normalize($_SESSION['_locale']);
        } elseif (isset($_COOKIE['dt_locale']) && is_string($_COOKIE['dt_locale'])) {
            $requested = self::normalize($_COOKIE['dt_locale']);
        } elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $requested = self::fromAcceptLanguage((string) $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        }

        self::setLocale($requested ?? self::DEFAULT_LOCALE);
    }

    public static function setLocale(string $locale): void
    {
        $locale = self::normalize($locale) ?? self::DEFAULT_LOCALE;
        self::$locale = $locale;
        $_SESSION['_locale'] = $locale;

        $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        setcookie('dt_locale', $locale, [
            'expires' => time() + 60 * 60 * 24 * 365,
            'path' => '/',
            'secure' => $isHttps,
            'httponly' => false,
            'samesite' => 'Lax',
        ]);

        $path = __DIR__ . '/lang/' . $locale . '.php';
        if (!is_file($path)) {
            $path = __DIR__ . '/lang/' . self::DEFAULT_LOCALE . '.php';
        }

        /** @var array<string, string> $messages */
        $messages = require $path;
        self::$messages = $messages;
    }

    public static function locale(): string
    {
        return self::$locale;
    }

    public static function htmlLang(): string
    {
        return match (self::$locale) {
            'en' => 'en',
            'es' => 'es',
            default => 'pt-BR',
        };
    }

    /**
     * @param array<string, string|int|float> $replace
     */
    public static function get(string $key, array $replace = []): string
    {
        $message = self::$messages[$key] ?? $key;

        foreach ($replace as $name => $value) {
            $message = str_replace(':' . $name, (string) $value, $message);
        }

        return $message;
    }

    public static function normalize(string $locale): ?string
    {
        $locale = str_replace('-', '_', trim($locale));
        $map = [
            'pt' => 'pt_BR',
            'pt_BR' => 'pt_BR',
            'pt_br' => 'pt_BR',
            'en' => 'en',
            'en_US' => 'en',
            'en_GB' => 'en',
            'es' => 'es',
            'es_ES' => 'es',
            'es_MX' => 'es',
        ];

        $candidate = $map[$locale] ?? $map[strtolower($locale)] ?? null;

        return in_array($candidate, self::LOCALES, true) ? $candidate : null;
    }

    private static function fromAcceptLanguage(string $header): ?string
    {
        foreach (explode(',', $header) as $part) {
            $code = trim(explode(';', $part)[0]);
            $normalized = self::normalize($code);
            if ($normalized !== null) {
                return $normalized;
            }
        }

        return null;
    }
}
