<?php

declare(strict_types=1);

namespace Signal\Blog;

use Bitrix\Main\Application;
use Bitrix\Main\Context;

/**
 * Генерация ссылок по именам маршрутов.
 *
 * Ошибки конфигурации роутинга не маскируются fallback-путями:
 * неизвестный маршрут — это ошибка разработчика, а не пользователя.
 */
final class Url
{
    /**
     * @param array<string, scalar> $params
     */
    public static function route(string $name, array $params = []): string
    {
        $url = Application::getInstance()->getRouter()->route($name, $params);

        if (!is_string($url) || $url === '')
        {
            throw new \InvalidArgumentException(sprintf('Маршрут "%s" не найден.', $name));
        }

        return $url;
    }

    public static function absolute(string $path): string
    {
        $request = Context::getCurrent()?->getRequest();
        $scheme = $request?->isHttps() ? 'https' : 'http';
        $host = (string)($request?->getServer()->getHttpHost() ?: 'localhost');

        return $scheme . '://' . $host . $path;
    }

    public static function blog(): string
    {
        return self::route('blog.index');
    }

    public static function category(string $code): string
    {
        return self::route('blog.category', ['section' => $code]);
    }

    public static function article(string $code): string
    {
        return self::route('blog.detail', ['code' => $code]);
    }

    public static function tag(string $tag): string
    {
        return self::blog() . '?' . http_build_query(['tag' => $tag], '', '&', PHP_QUERY_RFC3986);
    }
}
