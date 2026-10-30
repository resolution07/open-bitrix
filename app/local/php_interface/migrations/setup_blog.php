<?php

/**
 * Установка публичной части блога.
 *
 * Запуск из контейнера:
 *   docker compose exec -T php-fpm php /var/www/html/local/php_interface/migrations/setup_blog.php
 *
 * Скрипт регистрирует модуль signal.blog, создаёт инфоблок с демо-материалами
 * и привязывает шаблон blog к сайту s1.
 */

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_NO_ACCELERATE_RESETTER', true);
define('STOP_STATISTICS', true);

$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 3);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

\Bitrix\Main\Loader::includeModule('main');
\Bitrix\Main\Loader::includeModule('iblock');

$messages = [];

try
{
    if (\Bitrix\Main\ModuleManager::isModuleInstalled('blog'))
    {
        \Bitrix\Main\ModuleManager::unRegisterModule('blog');
        $messages[] = 'Старый модуль blog снят с регистрации.';
    }

    if (!\Bitrix\Main\ModuleManager::isModuleInstalled('signal.blog'))
    {
        \Bitrix\Main\ModuleManager::registerModule('signal.blog');
        $messages[] = 'Модуль signal.blog зарегистрирован.';
    }
    else
    {
        $messages[] = 'Модуль signal.blog уже зарегистрирован.';
    }

    require __DIR__ . '/create_blog_iblocks.php';

    $result = $GLOBALS['BLOG_DEMO_RESULT'] ?? [];
    $messages[] = 'Инфоблок blog_news: ID ' . (int)($result['iblockId'] ?? 0) . '.';
    $messages[] = 'Рубрики: ' . implode(', ', array_keys($result['sections'] ?? [])) . '.';

    $siteId = 's1';
    $template = 'blog';

    $binding = \Bitrix\Main\SiteTemplateTable::getList([
        'filter' => ['=SITE_ID' => $siteId, '=TEMPLATE' => $template],
        'limit' => 1,
    ])->fetch();

    if ($binding)
    {
        \Bitrix\Main\SiteTemplateTable::update((int)$binding['ID'], [
            'SORT' => 100,
            'CONDITION' => '',
        ]);
        $messages[] = 'Шаблон blog уже привязан к сайту ' . $siteId . '.';
    }
    else
    {
        $addResult = \Bitrix\Main\SiteTemplateTable::add([
            'SITE_ID' => $siteId,
            'TEMPLATE' => $template,
            'SORT' => 100,
            'CONDITION' => '',
        ]);

        if (!$addResult->isSuccess())
        {
            throw new \RuntimeException('Не удалось привязать шаблон: ' . implode('; ', $addResult->getErrorMessages()));
        }

        $messages[] = 'Шаблон blog привязан к сайту ' . $siteId . '.';
    }

    \Bitrix\Main\SiteTemplateTable::cleanCache();
    \Bitrix\Main\SiteTable::cleanCache();
    \Bitrix\Iblock\IblockTable::cleanCache();

    if (class_exists('BXClearCache'))
    {
        BXClearCache(true);
    }

    $managedCache = \Bitrix\Main\Application::getInstance()->getManagedCache();
    $managedCache->cleanAll();

    $messages[] = 'Кеш сайта очищен.';
}
catch (\Throwable $exception)
{
    fwrite(STDERR, "Ошибка: " . $exception->getMessage() . PHP_EOL);
    exit(1);
}

foreach ($messages as $message)
{
    echo $message . PHP_EOL;
}

echo 'Готово. Откройте /blog/ и /.' . PHP_EOL;
