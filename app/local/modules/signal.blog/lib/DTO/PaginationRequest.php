<?php

declare(strict_types=1);

namespace Signal\Blog\DTO;

/**
 * Запрос страницы в слои сервиса и репозитория.
 */
final readonly class PaginationRequest
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 7,
    ) {
        if ($this->perPage < 1)
        {
            throw new \InvalidArgumentException('Количество статей на странице должно быть больше нуля.');
        }
    }

    public function requestedPage(): int
    {
        return max(1, $this->page);
    }
}
