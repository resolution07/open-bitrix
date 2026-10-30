<?php

declare(strict_types=1);

namespace Signal\Blog\Service;

use Signal\Blog\DTO\Article;
use Signal\Blog\DTO\Category;
use Signal\Blog\DTO\Pagination;
use Signal\Blog\DTO\PaginationRequest;
use Signal\Blog\Presenter\ArticlePresenter;
use Signal\Blog\Repository\ArticleRepository;

/**
 * Прикладной сервис статей блога.
 *
 * Связывает данные репозитория с моделями представления.
 */
final class ArticleService
{
    public const PER_PAGE = 7;

    public function __construct(
        private readonly ArticleRepository $articles,
        private readonly CategoryService $categories,
        private readonly ArticlePresenter $presenter,
    ) {
    }

    /**
     * @return array{
     *     items: array<int, array{
     *         id: int,
     *         name: string,
     *         url: string,
     *         category: array{id: int, name: string, code: string, url: string, count: int},
     *         date: string,
     *         author: string,
     *         readingTime: string,
     *         excerpt: string,
     *         image: string
     *     }>,
     *     hero: array{
     *         id: int,
     *         name: string,
     *         url: string,
     *         category: array{id: int, name: string, code: string, url: string, count: int},
     *         date: string,
     *         author: string,
     *         readingTime: string,
     *         lead: string,
     *         heroImage: string
     *     }|null,
     *     pagination: Pagination
     * }
     */
    public function getListing(
        ?int $sectionId,
        string $search,
        string $tag,
        PaginationRequest $pagination,
        bool $withHero = false,
    ): array {
        $listing = $this->articles->getPage($sectionId, $search, $tag, $pagination);

        if ($listing->pagination->isOutOfRange)
        {
            return [
                'items' => [],
                'hero' => null,
                'pagination' => $listing->pagination,
            ];
        }

        $categories = $this->categoriesById($listing->items);
        $items = [];
        $hero = null;

        foreach ($listing->items as $index => $article)
        {
            $category = $categories[$article->sectionId] ?? Category::unknown();

            if ($withHero && $index === 0)
            {
                $hero = $this->presenter->hero($article, $category);

                continue;
            }

            $items[] = $this->presenter->card($article, $category);
        }

        return [
            'items' => $items,
            'hero' => $hero,
            'pagination' => $listing->pagination,
        ];
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
     * }|null
     */
    public function getByCode(string $code): ?array
    {
        $article = $this->articles->findByCode($code);

        if ($article === null)
        {
            return null;
        }

        return $this->presenter->details($article, $this->categoryFor($article));
    }

    /**
     * @return array<int, array{
     *     id: int,
     *     name: string,
     *     url: string,
     *     category: array{id: int, name: string, code: string, url: string, count: int},
     *     date: string,
     *     author: string,
     *     readingTime: string,
     *     image: string
     * }>
     */
    public function getReadNext(int $excludeId, int $limit = 3): array
    {
        $articles = $this->articles->getReadNext($excludeId, $limit);
        $categories = $this->categoriesById($articles);
        $items = [];

        foreach ($articles as $article)
        {
            $items[] = $this->presenter->readNext(
                $article,
                $categories[$article->sectionId] ?? Category::unknown()
            );
        }

        return $items;
    }

    private function categoryFor(Article $article): Category
    {
        return $this->categories->getById($article->sectionId) ?? Category::unknown();
    }

    /**
     * Загружает рубрики одним запросом на весь набор статей.
     *
     * @param array<int, Article> $articles
     *
     * @return array<int, Category> ID рубрики => рубрика
     */
    private function categoriesById(array $articles): array
    {
        $ids = [];

        foreach ($articles as $article)
        {
            if ($article->sectionId > 0)
            {
                $ids[] = $article->sectionId;
            }
        }

        return $this->categories->getByIds($ids);
    }
}
