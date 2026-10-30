<?php

use Bitrix\Main\Routing\RoutingConfigurator;
use Signal\Blog\Controller\BlogController;

/**
 * Маршруты публичной части блога.
 *
 * Физических страниц для этих адресов нет: обработчики — действия
 * контроллера Signal\Blog\Controller\BlogController из модуля signal.blog.
 *
 * Пространство имён Signal\Blog соответствует модулю signal.blog: загрузчик
 * регистрирует его автоматически при подключении модуля.
 */
return static function (RoutingConfigurator $routes): void
{
    $routes
        ->get('/', [BlogController::class, 'index'])
        ->name('blog.home')
    ;

    $routes
        ->get('/blog/', [BlogController::class, 'index'])
        ->name('blog.index')
    ;

    $routes
        ->get('/blog/page/{page}/', [BlogController::class, 'index'])
        ->where('page', '[1-9][0-9]*')
        ->name('blog.page')
    ;

    $routes
        ->get('/blog/category/{section}/', [BlogController::class, 'index'])
        ->where('section', '[a-z0-9\-]+')
        ->name('blog.category')
    ;

    $routes
        ->get('/blog/category/{section}/page/{page}/', [BlogController::class, 'index'])
        ->where('section', '[a-z0-9\-]+')
        ->where('page', '[1-9][0-9]*')
        ->name('blog.category.page')
    ;

    $routes
        ->get('/blog/{code}/', [BlogController::class, 'detail'])
        ->where('code', '[A-Za-z0-9_\-]+')
        ->name('blog.detail')
    ;
};
