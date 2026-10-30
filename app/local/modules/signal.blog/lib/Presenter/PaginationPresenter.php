<?php

declare(strict_types=1);

namespace Signal\Blog\Presenter;

use Bitrix\Main\UI\PageNavigation;
use Signal\Blog\DTO\Pagination;

/**
 * Построение модели пагинации для шаблона.
 *
 * Presenter не знает, какие именно фильтры используются на странице.
 * Он получает DTO пагинации, базовый URL и уже подготовленный набор
 * query-параметров, поэтому новые фильтры не требуют изменений в пагинации.
 */
final class PaginationPresenter
{
    /**
     * @param array<string, scalar> $query
     *
     * @return array{
     *     current: int,
     *     count: int,
     *     pages: array<int, array{type: string, number?: int, url?: string, current?: bool}>,
     *     prev: string|null,
     *     next: string|null
     * }
     */
    public function build(Pagination $pagination, string $baseUrl, array $query): array
    {
        $navigation = $this->navigation($pagination);
        $current = $navigation->getCurrentPage();
        $count = max(1, $navigation->getPageCount());

        if ($count <= 1)
        {
            return [
                'current' => 1,
                'count' => 1,
                'pages' => [],
                'prev' => null,
                'next' => null,
            ];
        }

        $url = fn(int $page): string => $this->pageUrl($baseUrl, $query, $page);
        $pages = [[
            'type' => 'page',
            'number' => 1,
            'url' => $url(1),
            'current' => $current === 1,
        ]];

        $start = max(2, $current - 2);
        $end = min($count - 1, $current + 2);

        if ($start > 2)
        {
            $pages[] = ['type' => 'dots'];
        }

        for ($page = $start; $page <= $end; $page++)
        {
            $pages[] = [
                'type' => 'page',
                'number' => $page,
                'url' => $url($page),
                'current' => $page === $current,
            ];
        }

        if ($end < $count - 1)
        {
            $pages[] = ['type' => 'dots'];
        }

        $pages[] = [
            'type' => 'page',
            'number' => $count,
            'url' => $url($count),
            'current' => $current === $count,
        ];

        return [
            'current' => $current,
            'count' => $count,
            'pages' => $pages,
            'prev' => $current > 1 ? $url($current - 1) : null,
            'next' => $current < $count ? $url($current + 1) : null,
        ];
    }

    /**
     * Ссылка на страницу списка в формате `/page/{page_number}/`.
     *
     * @param array<string, scalar> $query
     */
    public function pageUrl(string $baseUrl, array $query, int $page): string
    {
        $path = $page > 1
            ? rtrim($baseUrl, '/') . '/page/' . $page . '/'
            : $baseUrl;

        return $path . ($query !== []
            ? '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986)
            : '');
    }

    private function navigation(Pagination $pagination): PageNavigation
    {
        $navigation = new PageNavigation('page');

        $navigation
            ->setPageSize($pagination->perPage)
            ->setRecordCount($pagination->total)
            ->setCurrentPage($pagination->page)
        ;

        return $navigation;
    }
}
