<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

global $APPLICATION;

$currentPage = $APPLICATION->GetCurPage(false);
$pageTitle = $APPLICATION->GetTitle();
$pageDescription = $APPLICATION->GetPageProperty('description');
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialcharsbx(LANGUAGE_ID) ?>" data-theme="light">
<head>
    <meta charset="<?= htmlspecialcharsbx(LANG_CHARSET) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php $APPLICATION->ShowTitle(); ?></title>

    <script>
        try {
            const savedTheme = localStorage.getItem('signal-theme');
            if (savedTheme) {
                document.documentElement.dataset.theme = savedTheme;
            }
        } catch (error) {}
    </script>

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="СИГНАЛ">
    <meta property="og:title" content="<?= htmlspecialcharsbx($pageTitle) ?>">
    <?php if ($pageDescription): ?>
        <meta property="og:description" content="<?= htmlspecialcharsbx($pageDescription) ?>">
    <?php endif; ?>

    <?php $APPLICATION->ShowHead(); ?>
</head>
<body>
<div id="panel"><?php $APPLICATION->ShowPanel(); ?></div>

<a class="skip-link" href="#main-content">Перейти к содержимому</a>
<div class="reading-progress" aria-hidden="true"><span id="reading-progress-bar"></span></div>

<header class="site-header">
    <div class="container site-header__inner">
        <a class="logo" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>" aria-label="СИГНАЛ — на главную">
            <span class="logo__mark" aria-hidden="true">С</span>
            <span class="logo__word">СИГНАЛ<span class="logo__dot">.</span></span>
        </a>

        <nav class="site-nav" aria-label="Основная навигация">
            <a class="site-nav__link<?= ($currentPage === '/blog/' || $currentPage === '/') ? ' is-active' : '' ?>" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>">Журнал</a>
            <a class="site-nav__link<?= str_starts_with($currentPage, '/blog/category/tech/') ? ' is-active' : '' ?>" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('tech')) ?>">Технологии</a>
            <a class="site-nav__link<?= str_starts_with($currentPage, '/blog/category/design/') ? ' is-active' : '' ?>" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('design')) ?>">Дизайн</a>
            <a class="site-nav__link<?= str_starts_with($currentPage, '/blog/category/business/') ? ' is-active' : '' ?>" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('business')) ?>">Экономика</a>
            <a class="site-nav__link<?= str_starts_with($currentPage, '/blog/category/science/') ? ' is-active' : '' ?>" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('science')) ?>">Наука</a>
        </nav>

        <div class="site-header__actions">
            <button class="icon-button" type="button" id="theme-toggle" aria-label="Переключить тему">
                <svg class="icon-button__sun" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.7"/>
                    <path d="M12 2v2.2M12 19.8V22M2 12h2.2M19.8 12H22M4.9 4.9l1.6 1.6M17.5 17.5l1.6 1.6M19.1 4.9l-1.6 1.6M6.5 17.5l-1.6 1.6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                </svg>
                <svg class="icon-button__moon" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M20.2 14.2A8.5 8.5 0 0 1 9.8 3.8 8.5 8.5 0 1 0 20.2 14.2Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                </svg>
            </button>

            <button class="icon-button menu-toggle" type="button" id="menu-toggle" aria-label="Открыть меню" aria-expanded="false" aria-controls="mobile-menu">
                <span></span><span></span>
            </button>
        </div>
    </div>

    <div class="mobile-menu" id="mobile-menu" hidden>
        <nav class="mobile-menu__nav" aria-label="Мобильная навигация">
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>">Журнал</a>
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('tech')) ?>">Технологии</a>
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('design')) ?>">Дизайн</a>
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('business')) ?>">Экономика</a>
            <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::category('science')) ?>">Наука</a>
        </nav>
        <p class="mobile-menu__note">Независимый журнал о технологиях, дизайне и людях.</p>
    </div>
</header>

<main id="main-content" class="site-main">
