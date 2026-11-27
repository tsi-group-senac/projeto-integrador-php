<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? __('meta.default_title');
$pageDescription = $pageDescription ?? __('meta.default_description');
$favicons = app_url('/frontend/assets/favicons');
?>
<!doctype html>
<html lang="<?= e(I18n::htmlLang()) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($pageDescription) ?>">
    <title><?= e($pageTitle) ?></title>
    <!-- Favicons (pacote estilo RealFaviconGenerator) -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= e($favicons) ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= e($favicons) ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= e($favicons) ?>/favicon-16x16.png">
    <link rel="icon" type="image/x-icon" href="<?= e($favicons) ?>/favicon.ico">
    <link rel="manifest" href="<?= e($favicons) ?>/site.webmanifest">
    <link rel="mask-icon" href="<?= e($favicons) ?>/safari-pinned-tab.svg" color="#faa935">
    <meta name="msapplication-TileColor" content="#faa935">
    <meta name="msapplication-config" content="<?= e($favicons) ?>/browserconfig.xml">
    <meta name="theme-color" content="#faa935">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dt: {
                            orange: '#faa935',
                            ink: '#0b2727',
                            muted: '#6e7074',
                            sky: '#cae5f4',
                        },
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        script: ['"Island Moments"', 'cursive'],
                    },
                    maxWidth: {
                        site: '1440px',
                        content: '1120px',
                    },
                    boxShadow: {
                        search: '0 18px 40px rgba(11, 39, 39, 0.12)',
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Island+Moments&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(app_url('/frontend/css/styles.css')) ?>">
</head>
<body class="min-h-screen bg-white font-sans text-dt-ink antialiased">
