<?php

/**
 * Дополнительная конфигурация проекта.
 *
 * Файл дополняет /bitrix/.settings.php и включает современный роутинг.
 * Маршруты находятся в /local/routes/web.php.
 */
return [
    'routing' => [
        'value' => [
            'config' => ['web.php'],
        ],
        'readonly' => true,
    ],
];
