<?php

/** @var array $article */
/** @var array $related */
/** @var string $shareUrl */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

$category = $article['category'];
$shareTitle = rawurlencode($article['name']);
$encodedShareUrl = rawurlencode($shareUrl);
?>
<article class="article" id="article">
    <div class="container">
        <header class="article__header">
            <nav class="breadcrumbs" aria-label="Хлебные крошки">
                <a href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>">Журнал</a>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="m9 5 7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <?php if ($category['code'] !== ''): ?>
                    <a href="<?= htmlspecialcharsbx($category['url']) ?>"><?= htmlspecialcharsbx($category['name']) ?></a>
                <?php else: ?>
                    <span>Статья</span>
                <?php endif; ?>
            </nav>

            <p class="eyebrow"><?= htmlspecialcharsbx($category['name']) ?></p>
            <h1 class="article__title"><?= htmlspecialcharsbx($article['name']) ?></h1>

            <?php if ($article['previewText'] !== ''): ?>
                <p class="article__lead"><?= htmlspecialcharsbx($article['previewText']) ?></p>
            <?php endif; ?>

            <div class="article__meta">
                <div class="article__author">
                    <span class="article__author-avatar" aria-hidden="true"><?= htmlspecialcharsbx(mb_substr($article['author'], 0, 1)) ?></span>
                    <span>
                        <span class="article__author-name"><?= htmlspecialcharsbx($article['author']) ?></span>
                        <span class="article__author-role"><?= htmlspecialcharsbx($article['authorRole']) ?></span>
                    </span>
                </div>
                <div class="meta">
                    <span><?= htmlspecialcharsbx($article['date']) ?></span>
                    <span><?= htmlspecialcharsbx($article['readingTime']) ?> чтения</span>
                </div>
            </div>
        </header>

        <?php if ($article['cover'] !== ''): ?>
            <figure class="article__cover">
                <img src="<?= htmlspecialcharsbx($article['cover']) ?>" alt="<?= htmlspecialcharsbx($article['name']) ?>" fetchpriority="high">
            </figure>
        <?php endif; ?>

        <div class="article__body">
            <?= $article['content'] ?>
        </div>

        <footer class="article__footer">
            <?php if ($article['tags'] !== []): ?>
                <ul class="tag-list" aria-label="Теги статьи">
                    <?php foreach ($article['tags'] as $tag): ?>
                        <li><a class="chip" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::tag($tag)) ?>">#<?= htmlspecialcharsbx($tag) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="article__share">
                <span class="article__share-label">Поделиться</span>
                <div class="share-buttons">
                    <button class="share-button" type="button" data-copy-link="Скопировать ссылку">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M10 13a5 5 0 0 0 7.5.5l2-2a5 5 0 0 0-7-7l-1.1 1.1" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                            <path d="M14 11a5 5 0 0 0-7.5-.5l-2 2a5 5 0 0 0 7 7l1.1-1.1" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                        </svg>
                        Скопировать ссылку
                    </button>
                    <a class="share-button" href="https://t.me/share/url?url=<?= $encodedShareUrl ?>&text=<?= $shareTitle ?>" target="_blank" rel="noopener noreferrer">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m21 4-3.4 15.2c-.2 1-.9 1.2-1.7.7l-4.7-3.5-2.3 2.2c-.2.2-.5.5-1 .5l.3-4.9 8.7-7.9c.4-.3-.1-.5-.6-.2L6.8 12.6 2.5 11.3c-.9-.3-.9-.9.2-1.3L19.8 3c.8-.3 1.5.2 1.2 1Z" fill="currentColor"/>
                        </svg>
                        Telegram
                    </a>
                    <a class="share-button" href="https://vk.com/share.php?url=<?= $encodedShareUrl ?>" target="_blank" rel="noopener noreferrer">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12.8 17.5c-5 0-8.1-3.5-8.2-9.2h2.6c.1 4.2 2 6 3.5 6.4V8.3H13v3.7c1.4-.2 2.9-1.9 3.4-3.7h2.5c-.4 2.2-1.9 3.9-2.9 4.5 1 .5 2.7 2 3.3 4.7h-2.7c-.5-1.7-1.8-3-3.1-3.2v3.2h-.7Z" fill="currentColor"/>
                        </svg>
                        ВКонтакте
                    </a>
                </div>
            </div>

            <div class="article__author-box">
                <span class="article__author-avatar" aria-hidden="true"><?= htmlspecialcharsbx(mb_substr($article['author'], 0, 1)) ?></span>
                <div>
                    <h2><?= htmlspecialcharsbx($article['author']) ?></h2>
                    <p><?= htmlspecialcharsbx($article['authorRole']) ?>. Пишет о технологиях, культуре и том, как изменения входят в повседневную жизнь.</p>
                </div>
            </div>
        </footer>
    </div>

    <?php if ($related !== []): ?>
        <section class="related" aria-labelledby="related-title">
            <div class="related__head">
                <h2 class="related__title" id="related-title">Читать дальше</h2>
                <a class="related__all" href="<?= htmlspecialcharsbx(\Signal\Blog\Url::blog()) ?>">Все материалы →</a>
            </div>

            <div class="related__grid">
                <?php foreach ($related as $item): ?>
                    <article class="post-card">
                        <a class="post-card__media" href="<?= htmlspecialcharsbx($item['url']) ?>" tabindex="-1" aria-hidden="true">
                            <img src="<?= htmlspecialcharsbx($item['image']) ?>" alt="<?= htmlspecialcharsbx($item['name']) ?>" loading="lazy">
                        </a>
                        <div class="post-card__body">
                            <div class="post-card__top">
                                <a class="chip" href="<?= htmlspecialcharsbx($item['category']['url']) ?>"><?= htmlspecialcharsbx($item['category']['name']) ?></a>
                                <span class="post-card__date"><?= htmlspecialcharsbx($item['date']) ?></span>
                            </div>
                            <h3 class="post-card__title">
                                <a href="<?= htmlspecialcharsbx($item['url']) ?>"><?= htmlspecialcharsbx($item['name']) ?></a>
                            </h3>
                            <div class="post-card__footer">
                                <span><?= htmlspecialcharsbx($item['readingTime']) ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</article>
