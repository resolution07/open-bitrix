<?php

declare(strict_types=1);

namespace Signal\Blog\Presenter;

use Signal\Blog\DTO\Category;

/**
 * Заголовок, описание и SEO-признаки страницы списка.
 */
final class PageMetadataPresenter
{
    /**
     * @return array{
     *     title: string,
     *     eyebrow: string,
     *     description: string,
     *     showHero: bool,
     *     noindex: bool
     * }
     */
    public function forListing(
        ?Category $section,
        string $search,
        string $tag,
        int $requestedPage,
    ): array {
        if ($tag !== '')
        {
            return [
                'title' => 'Тег: #' . $tag,
                'eyebrow' => 'Материалы по тегу',
                'description' => 'Все материалы с тегом «' . $tag . '».',
                'showHero' => false,
                'noindex' => false,
            ];
        }

        if ($search !== '')
        {
            return [
                'title' => 'Поиск: ' . $search,
                'eyebrow' => 'Результаты поиска',
                'description' => 'Материалы, в названии которых есть «' . $search . '».',
                'showHero' => false,
                'noindex' => true,
            ];
        }

        if ($section !== null)
        {
            return [
                'title' => $section->name,
                'eyebrow' => 'Рубрика',
                'description' => $section->description !== ''
                    ? $section->description
                    : 'Все материалы рубрики «' . $section->name . '».',
                'showHero' => false,
                'noindex' => false,
            ];
        }

        return [
            'title' => 'Журнал о том, что важно',
            'eyebrow' => 'СИГНАЛ · независимый журнал',
            'description' => 'Технологии, дизайн, экономика и наука — без шума и лишних слов.',
            'showHero' => $requestedPage <= 1,
            'noindex' => false,
        ];
    }
}
