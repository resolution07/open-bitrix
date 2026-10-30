<?php

/**
 * Точка входа сайта.
 *
 * Корневой адрес обслуживается современным роутингом Bitrix:
 * маршрут "/" описан в /local/routes/web.php и открывает блог.
 */

$requestUri = (string)($_SERVER['REQUEST_URI'] ?? '/');
$requestPath = (string)(parse_url($requestUri, PHP_URL_PATH) ?: '/');

if ($requestPath === '/index.php')
{
    $query = parse_url($requestUri, PHP_URL_QUERY);

    header('Location: /' . ($query ? '?' . $query : ''), true, 301);
    exit;
}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/routing_index.php';