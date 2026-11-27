<?php

declare(strict_types=1);

/**
 * @return array{page: int, per_page: int, total: int, pages: int, offset: int}
 */
function pagination_meta(int $total, ?int $page = null, int $perPage = 10): array
{
    $perPage = max(1, $perPage);
    $pages = max(1, (int) ceil($total / $perPage));
    $current = $page ?? (int) ($_GET['page'] ?? 1);
    $current = max(1, min($current, $pages));

    return [
        'page' => $current,
        'per_page' => $perPage,
        'total' => max(0, $total),
        'pages' => $pages,
        'offset' => ($current - 1) * $perPage,
    ];
}

function pagination_url(int $page): string
{
    $params = $_GET;
    $params['page'] = $page;
    $path = (string) (parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH) ?: '');

    return $path . '?' . http_build_query($params);
}
