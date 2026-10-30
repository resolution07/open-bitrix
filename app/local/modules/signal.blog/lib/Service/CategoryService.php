<?php

declare(strict_types=1);

namespace Signal\Blog\Service;

use Signal\Blog\DTO\Category;
use Signal\Blog\Repository\ArticleRepository;
use Signal\Blog\Repository\CategoryRepository;

/**
 * Рубрики блога.
 *
 * Прикладной слой: кеширует справочник в пределах запроса и добавляет
 * к рубрикам количество опубликованных материалов.
 */
final class CategoryService
{
    /** @var array<int, Category>|null */
    private ?array $categories = null;

    /** @var array<int, Category> */
    private array $byId = [];

    /** @var array<string, Category> */
    private array $byCode = [];

    public function __construct(
        private readonly CategoryRepository $repository,
        private readonly ArticleRepository $articles,
    ) {
    }

    /**
     * @return array<int, Category>
     */
    public function getCategories(): array
    {
        if ($this->categories !== null)
        {
            return $this->categories;
        }

        $counts = $this->articles->countsBySection();
        $categories = [];

        foreach ($this->repository->findAll() as $category)
        {
            $category = $this->remember(
                $category->withCount((int)($counts[$category->id] ?? 0))
            );
            $categories[] = $category;
        }

        return $this->categories = $categories;
    }

    public function find(string $code): ?Category
    {
        $this->getCategories();

        return $this->byCode[$code] ?? null;
    }

    public function getById(int $id): ?Category
    {
        return $this->getByIds([$id])[$id] ?? null;
    }

    /**
     * @param array<int, int> $ids
     *
     * @return array<int, Category> ID рубрики => рубрика
     */
    public function getByIds(array $ids): array
    {
        $ids = array_values(array_unique(array_filter(
            array_map('intval', $ids),
            static fn(int $id): bool => $id > 0
        )));

        if ($ids === [])
        {
            return [];
        }

        $result = [];

        foreach ($ids as $id)
        {
            if (isset($this->byId[$id]))
            {
                $result[$id] = $this->byId[$id];
            }
        }

        if ($this->categories !== null)
        {
            return $result;
        }

        $missing = array_values(array_filter(
            $ids,
            fn(int $id): bool => !isset($this->byId[$id])
        ));

        if ($missing !== [])
        {
            foreach ($this->repository->findByIds($missing) as $id => $category)
            {
                $result[$id] = $this->remember($category);
            }
        }

        return $result;
    }

    private function remember(Category $category): Category
    {
        $this->byId[$category->id] = $category;
        $this->byCode[$category->code] = $category;

        return $category;
    }
}
