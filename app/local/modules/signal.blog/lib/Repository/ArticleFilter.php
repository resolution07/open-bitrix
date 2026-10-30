<?php

declare(strict_types=1);

namespace Signal\Blog\Repository;

use Bitrix\Main\Type\DateTime;

/**
 * Фильтры выборки опубликованных статей.
 *
 * Условия собраны в одном месте, чтобы список, карточка статьи и счётчики
 * рубрик всегда использовали одинаковое определение публикации.
 */
final class ArticleFilter
{
    /**
     * @return array<int|string, mixed>
     */
    public static function published(): array
    {
        $now = new DateTime();

        return [
            '=ACTIVE' => 'Y',
            [
                'LOGIC' => 'OR',
                '<=ACTIVE_FROM' => $now,
                '=ACTIVE_FROM' => null,
            ],
            [
                'LOGIC' => 'OR',
                '>=ACTIVE_TO' => $now,
                '=ACTIVE_TO' => null,
            ],
        ];
    }

    /**
     * @return array<int|string, mixed>
     */
    public static function forListing(?int $sectionId, string $search, string $tag = ''): array
    {
        $filter = self::published();

        if ($sectionId !== null && $sectionId > 0)
        {
            $filter['=IBLOCK_SECTION_ID'] = $sectionId;
        }

        $search = trim($search);

        if ($search !== '')
        {
            $filter['%NAME'] = $search;
        }

        $tag = trim($tag);

        if ($tag !== '')
        {
            $filter['=ARTICLE_TAGS.VALUE'] = $tag;
        }

        return $filter;
    }
}
