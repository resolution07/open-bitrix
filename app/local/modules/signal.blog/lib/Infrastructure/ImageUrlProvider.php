<?php

declare(strict_types=1);

namespace Signal\Blog\Infrastructure;

/**
 * Подготовка URL изображений инфоблока.
 *
 * Классическое API Bitrix используется здесь только для ресайза файлов.
 * Результат кешируется в пределах запроса: инвалидация не требуется,
 * потому что кеш живёт только до конца текущего запроса.
 */
final class ImageUrlProvider
{
    /** @var array<string, string|null> */
    private array $cache = [];

    public function url(int $fileId, int $width, int $height): ?string
    {
        if ($fileId <= 0)
        {
            return null;
        }

        $key = $fileId . ':' . $width . ':' . $height;

        if (array_key_exists($key, $this->cache))
        {
            return $this->cache[$key];
        }

        $resized = \CFile::ResizeImageGet(
            $fileId,
            ['width' => $width, 'height' => $height],
            BX_RESIZE_IMAGE_EXACT,
            true
        );

        $src = is_array($resized) ? trim((string)($resized['src'] ?? '')) : '';

        if ($src !== '')
        {
            return $this->cache[$key] = $src;
        }

        $path = \CFile::GetPath($fileId);
        $path = is_string($path) ? trim($path) : '';

        return $this->cache[$key] = $path !== '' ? $path : null;
    }
}
