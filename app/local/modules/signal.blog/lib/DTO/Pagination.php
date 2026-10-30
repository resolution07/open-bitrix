<?php

declare(strict_types=1);

namespace Signal\Blog\DTO;

/**
 * Состояние пагинации, которое репозиторий возвращает сервису и контроллеру.
 *
 * `requestedPage` хранит номер из URL, а `page` — фактическую страницу.
 * Для несуществующей страницы `isOutOfRange` равен true и `items` не выбираются.
 */
final readonly class Pagination
{
    public function __construct(
        public int $page,
        public int $pageCount,
        public int $perPage,
        public int $total,
        public int $requestedPage,
        public bool $isOutOfRange,
    ) {
    }
}
