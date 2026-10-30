<?php

/** @var array $categories */
/** @var array|null $activeSection */
/** @var string $searchQuery */
/** @var string $tagQuery */
/** @var string $pageTitle */
/** @var string $eyebrow */
/** @var string $description */
/** @var array|null $hero */
/** @var array $articles */
/** @var array $pagination */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

$activeSectionCode = $activeSection['code'] ?? '';
?>
<section class="blog-hero">
    <div class="container blog-hero__inner">
        <p class="eyebrow"><?= htmlspecialcharsbx($eyebrow) ?></p>
        <h1 class="blog-hero__title"><?= htmlspecialcharsbx($pageTitle) ?></h1>
        <p class="blog-hero__lead"><?= htmlspecialcharsbx($description) ?></p>

        <form class="search" action="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>" method="get" role="search">
            <label class="visually-hidden" for="blog-search">Поиск по журналу</label>
            <svg class="search__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/>
                <path d="m16.5 16.5 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            <input
                id="blog-search"
                class="search__input"
                type="search"
                name="q"
                value="<?= htmlspecialcharsbx($searchQuery) ?>"
                placeholder="Найти статью"
                autocomplete="off"
            >
            <button class="search__submit" type="submit">Искать</button>
        </form>

        <?php if ($searchQuery !== '' || $tagQuery !== ''): ?>
            <a class="search-reset" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>">Сбросить фильтр</a>
        <?php endif; ?>
    </div>
</section>

<div class="container blog-main">
    <nav class="category-chips" aria-label="Рубрики журнала">
        <a class="chip<?= $activeSectionCode === '' ? ' is-active' : '' ?>" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>">
            Все
        </a>
        <?php foreach ($categories as $category): ?>
            <?php $isActive = $activeSectionCode !== '' && $activeSectionCode === $category['code']; ?>
            <a class="chip<?= $isActive ? ' is-active' : '' ?>" href="<?= htmlspecialcharsbx($category['url']) ?>">
                <?= htmlspecialcharsbx($category['name']) ?>
                <?php if ($category['count'] > 0): ?><span class="chip__count"><?= (int)$category['count'] ?></span><?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <?php if ($hero !== null): ?>
        <article class="hero-card">
            <a class="hero-card__media" href="<?= htmlspecialcharsbx($hero['url']) ?>" tabindex="-1" aria-hidden="true">
                <img src="<?= htmlspecialcharsbx($hero['heroImage']) ?>" alt="<?= htmlspecialcharsbx($hero['name']) ?>" fetchpriority="high">
            </a>
            <div class="hero-card__body">
                <a class="chip chip--accent" href="<?= htmlspecialcharsbx($hero['category']['url']) ?>">
                    <?= htmlspecialcharsbx($hero['category']['name']) ?>
                </a>
                <h2 class="hero-card__title">
                    <a href="<?= htmlspecialcharsbx($hero['url']) ?>"><?= htmlspecialcharsbx($hero['name']) ?></a>
                </h2>
                <p class="hero-card__excerpt"><?= htmlspecialcharsbx($hero['lead']) ?></p>
                <div class="meta">
                    <span><?= htmlspecialcharsbx($hero['date']) ?></span>
                    <span><?= htmlspecialcharsbx($hero['readingTime']) ?></span>
                    <span><?= htmlspecialcharsbx($hero['author']) ?></span>
                </div>
                <a class="read-link" href="<?= htmlspecialcharsbx($hero['url']) ?>">
                    Читать статью
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </article>
    <?php endif; ?>

    <?php if ($articles !== []): ?>
        <div class="post-grid">
            <?php foreach ($articles as $article): ?>
                <article class="post-card">
                    <a class="post-card__media" href="<?= htmlspecialcharsbx($article['url']) ?>" tabindex="-1" aria-hidden="true">
                        <img src="<?= htmlspecialcharsbx($article['image']) ?>" alt="<?= htmlspecialcharsbx($article['name']) ?>" loading="lazy">
                    </a>
                    <div class="post-card__body">
                        <div class="post-card__top">
                            <a class="chip" href="<?= htmlspecialcharsbx($article['category']['url']) ?>"><?= htmlspecialcharsbx($article['category']['name']) ?></a>
                            <span class="post-card__date"><?= htmlspecialcharsbx($article['date']) ?></span>
                        </div>
                        <h3 class="post-card__title">
                            <a href="<?= htmlspecialcharsbx($article['url']) ?>"><?= htmlspecialcharsbx($article['name']) ?></a>
                        </h3>
                        <p class="post-card__excerpt"><?= htmlspecialcharsbx($article['excerpt']) ?></p>
                        <div class="post-card__footer">
                            <span class="post-card__author">
                                <span class="author-dot"><?= htmlspecialcharsbx(mb_substr($article['author'], 0, 1)) ?></span>
                                <?= htmlspecialcharsbx($article['author']) ?>
                            </span>
                            <span><?= htmlspecialcharsbx($article['readingTime']) ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php elseif ($hero === null): ?>
        <div class="empty-state">
            <?php if ($tagQuery !== ''): ?>
                <h2>Ничего не нашлось</h2>
                <p>По тегу «<?= htmlspecialcharsbx($tagQuery) ?>» материалов нет. Попробуйте другую рубрику.</p>
            <?php elseif ($searchQuery !== ''): ?>
                <h2>Ничего не нашлось</h2>
                <p>По запросу «<?= htmlspecialcharsbx($searchQuery) ?>» материалов нет. Попробуйте другую формулировку.</p>
            <?php else: ?>
                <h2>Здесь пока тихо</h2>
                <p>В этой рубрике ещё нет опубликованных материалов.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($pagination['count'] > 1): ?>
        <nav class="pagination" aria-label="Навигация по страницам">
            <?php if ($pagination['prev'] !== null): ?>
                <a class="pagination__link pagination__link--arrow" href="<?= htmlspecialcharsbx($pagination['prev']) ?>" rel="prev">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="visually-hidden">Назад</span>
                </a>
            <?php else: ?>
                <span class="pagination__link pagination__link--arrow pagination__link--disabled" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            <?php endif; ?>

            <?php foreach ($pagination['pages'] as $page): ?>
                <?php if ($page['type'] === 'dots'): ?>
                    <span class="pagination__dots" aria-hidden="true">…</span>
                <?php elseif ($page['current']): ?>
                    <span class="pagination__current" aria-current="page"><?= (int)$page['number'] ?></span>
                <?php else: ?>
                    <a class="pagination__link" href="<?= htmlspecialcharsbx($page['url']) ?>"><?= (int)$page['number'] ?></a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($pagination['next'] !== null): ?>
                <a class="pagination__link pagination__link--arrow" href="<?= htmlspecialcharsbx($pagination['next']) ?>" rel="next">
                    <span class="visually-hidden">Вперёд</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            <?php else: ?>
                <span class="pagination__link pagination__link--arrow pagination__link--disabled" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</div>
