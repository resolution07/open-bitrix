<?php

declare(strict_types=1);

namespace Signal\Blog\DTO;

/**
 * Страница списка статей.
 */
final readonly class ArticlePage
{
    /**
     * @param array<int, Article> $items
     */
    public function __construct(
        public array $items,
        public Pagination $pagination,
    ) {
    }
}
