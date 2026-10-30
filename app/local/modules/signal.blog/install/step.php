<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}
?>
<div class="adm-info-message">
    <p>Модуль «Новостной блог» зарегистрирован.</p>
    <p>
        Публичная часть и демо-контент создаются отдельным обязательным шагом.
        Выполните в контейнере:
    </p>
    <pre>docker compose exec -T php-fpm php /var/www/html/local/php_interface/migrations/setup_blog.php</pre>
    <p>После этого журнал будет доступен по адресу <a href="/blog/">/blog/</a>.</p>
</div>
