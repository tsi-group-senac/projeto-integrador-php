<?php

declare(strict_types=1);

require_once __DIR__ . '/../../backend/bootstrap.php';

$lang = isset($_GET['lang']) ? (string) $_GET['lang'] : I18n::DEFAULT_LOCALE;
I18n::setLocale($lang);

$fallback = app_url('/frontend/index.php');
$redirect = isset($_GET['redirect']) ? (string) $_GET['redirect'] : ((string) ($_SERVER['HTTP_REFERER'] ?? $fallback));

$parts = parse_url($redirect);
$path = is_array($parts) && isset($parts['path']) ? (string) $parts['path'] : '/frontend/index.php';
$query = is_array($parts) && isset($parts['query']) ? (string) $parts['query'] : '';

// Drop existing lang query param from redirect target.
if ($query !== '') {
    parse_str($query, $params);
    unset($params['lang']);
    $query = http_build_query($params);
}

$base = project_base_path();
if ($base !== '' && str_starts_with($path, $base)) {
    $target = $path . ($query !== '' ? '?' . $query : '');
} elseif (str_starts_with($path, '/frontend') || $path === '/' || $path === '') {
    $normalized = $path === '/' || $path === '' ? '/frontend/index.php' : $path;
    $target = app_url($normalized) . ($query !== '' ? '?' . $query : '');
} else {
    $target = $fallback;
}

redirect($target);
