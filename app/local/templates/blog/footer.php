<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}
?>
</main>

<footer class="site-footer">
    <div class="container site-footer__top">
        <div class="site-footer__brand">
            <a class="logo logo--footer" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>">
                <span class="logo__mark" aria-hidden="true">С</span>
                <span class="logo__word">СИГНАЛ<span class="logo__dot">.</span></span>
            </a>
            <p>Независимый журнал о технологиях, дизайне, экономике и науке. Без шума и лишних слов.</p>
        </div>

        <div class="site-footer__col">
            <h2>Рубрики</h2>
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('tech')) ?>">Технологии</a>
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('design')) ?>">Дизайн</a>
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('business')) ?>">Экономика</a>
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('science')) ?>">Наука</a>
        </div>

        <div class="site-footer__col">
            <h2>Журнал</h2>
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>">Все материалы</a>
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>?q=редакция">Редакция</a>
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>?q=контакты">Контакты</a>
        </div>

        <form class="subscribe" id="subscribe-form" novalidate>
            <label class="subscribe__label" for="subscribe-email">Письма без шума</label>
            <div class="subscribe__row">
                <input class="subscribe__input" id="subscribe-email" type="email" name="email" placeholder="you@example.com" autocomplete="email" required>
                <button class="subscribe__button" type="submit" aria-label="Подписаться">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            <p class="subscribe__note" id="subscribe-note">Одно письмо в неделю. Без спама.</p>
        </form>
    </div>

    <div class="container site-footer__bottom">
        <span>© <?= date('Y') ?> СИГНАЛ</span>
        <span>Сделано с вниманием к деталям</span>
    </div>
</footer>

<script src="<?= SITE_TEMPLATE_PATH ?>/script.js" defer></script>
</body>
</html>
