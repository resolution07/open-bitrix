<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}
?>
<section class="not-found">
    <div class="container not-found__inner">
        <p class="eyebrow">Ошибка 404</p>
        <h1 class="not-found__code">404</h1>
        <p class="not-found__text">Такой материал не найден. Возможно, он был удалён или ссылка устарела.</p>
        <a class="button button--accent" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>">Вернуться в журнал</a>
    </div>
</section>
