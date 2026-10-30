<?php

declare(strict_types=1);

namespace Signal\Blog\Presenter;

use Signal\Blog\DTO\Article;
use Signal\Blog\DTO\Category;
use Signal\Blog\Infrastructure\ImageUrlProvider;
use Signal\Blog\Url;
use Bitrix\Main\Type\DateTime;

/**
 * Преобразование статьи в модель для шаблона.
 */
final class ArticlePresenter
{
    public function __construct(
        private readonly CategoryPresenter $categories,
        private readonly ImageUrlProvider $images,
    ) {
    }

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     url: string,
     *     category: array{id: int, name: string, code: string, url: string, count: int},
     *     date: string,
     *     author: string,
     *     readingTime: string,
     *     excerpt: string,
     *     image: string
     * }
     */
    public function card(Article $article, Category $category): array
    {
        return array_merge($this->common($article, $category), [
            'excerpt' => $this->excerpt($this->plainText($article), 190),
            'image' => $this->image($article->cardPictureId(), 800, 600),
        ]);
    }

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     url: string,
     *     category: array{id: int, name: string, code: string, url: string, count: int},
     *     date: string,
     *     author: string,
     *     readingTime: string,
     *     lead: string,
     *     heroImage: string
     * }
     */
    public function hero(Article $article, Category $category): array
    {
        return array_merge($this->common($article, $category), [
            'lead' => $this->excerpt($this->plainText($article), 260),
            'heroImage' => $this->image($article->cardPictureId(), 1200, 1000),
        ]);
    }

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     url: string,
     *     category: array{id: int, name: string, code: string, url: string, count: int},
     *     date: string,
     *     author: string,
     *     readingTime: string,
     *     authorRole: string,
     *     previewText: string,
     *     tags: array<int, string>,
     *     content: string,
     *     cover: string
     * }
     */
    public function details(Article $article, Category $category): array
    {
        return array_merge($this->common($article, $category), [
            'authorRole' => $article->authorRole !== '' ? $article->authorRole : 'Автор материала',
            'previewText' => $article->previewText,
            'tags' => $article->tags,
            'content' => $article->detailText !== '' ? $article->detailText : $article->previewText,
            'cover' => $this->image($article->coverPictureId(), 1600, 1000),
        ]);
    }

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     url: string,
     *     category: array{id: int, name: string, code: string, url: string, count: int},
     *     date: string,
     *     author: string,
     *     readingTime: string,
     *     image: string
     * }
     */
    public function readNext(Article $article, Category $category): array
    {
        return array_merge($this->common($article, $category), [
            'image' => $this->image($article->cardPictureId(), 800, 600),
        ]);
    }

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     url: string,
     *     category: array{id: int, name: string, code: string, url: string, count: int},
     *     date: string,
     *     author: string,
     *     readingTime: string
     * }
     */
    private function common(Article $article, Category $category): array
    {
        return [
            'id' => $article->id,
            'name' => $article->name,
            'url' => Url::article($article->code),
            'category' => $this->categories->toArray($category),
            'date' => $this->date($article->activeFrom),
            'author' => $article->author !== '' ? $article->author : 'Редакция',
            'readingTime' => $article->readingTime !== '' ? $article->readingTime : '5 мин',
        ];
    }

    private function plainText(Article $article): string
    {
        return $article->previewText !== ''
            ? trim(strip_tags($article->previewText))
            : trim(strip_tags($article->detailText));
    }

    private function date(?DateTime $date): string
    {
        return $date !== null ? FormatDate('j F Y', $date->getTimestamp()) : '';
    }

    private function excerpt(string $text, int $limit): string
    {
        $text = trim($text);

        if (mb_strlen($text) > $limit)
        {
            $text = mb_substr($text, 0, $limit) . '…';
        }

        return $text;
    }

    private function image(int $fileId, int $width, int $height): string
    {
        $url = $this->images->url($fileId, $width, $height);

        return $url !== null && $url !== '' ? $url : $this->placeholder();
    }

    private function placeholder(): string
    {
        return defined('SITE_TEMPLATE_PATH')
            ? SITE_TEMPLATE_PATH . '/images/placeholder.svg'
            : '/local/templates/blog/images/placeholder.svg';
    }
}
