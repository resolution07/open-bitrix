<?php

declare(strict_types=1);

namespace Signal\Blog\Repository;

use Signal\Blog\DTO\Article;
use Signal\Blog\DTO\ArticlePage;
use Signal\Blog\DTO\Pagination;
use Signal\Blog\DTO\PaginationRequest;
use Signal\Blog\Infrastructure\IblockEntityProvider;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\Type\DateTime;

/**
 * ORM-доступ к статьям блога.
 *
 * Репозиторий не знает о URL, шаблонах и изображениях — только данные.
 */
final class ArticleRepository
{
    private const SELECT = [
        'ID',
        'NAME',
        'CODE',
        'ACTIVE_FROM',
        'PREVIEW_TEXT',
        'DETAIL_TEXT',
        'PREVIEW_PICTURE',
        'DETAIL_PICTURE',
        'IBLOCK_SECTION_ID',
        'AUTHOR',
        'AUTHOR_ROLE',
        'READING_TIME',
        'ARTICLE_TAGS',
    ];

    public function __construct(private readonly IblockEntityProvider $iblocks)
    {
    }

    public function getPage(
        ?int $sectionId,
        string $search,
        string $tag,
        PaginationRequest $request,
    ): ArticlePage {
        $elementClass = $this->iblocks->getElementClass();
        $filter = ArticleFilter::forListing($sectionId, $search, $tag);
        $order = $this->listingOrder();
        $requestedPage = $request->requestedPage();

        $total = (int)$elementClass::getList([
            'select' => ['ID'],
            'filter' => $filter,
            'count_total' => true,
        ])->getCount();

        $pageCount = max(1, (int)ceil($total / $request->perPage));

        if ($requestedPage > $pageCount)
        {
            return new ArticlePage(
                [],
                new Pagination(
                    $pageCount,
                    $pageCount,
                    $request->perPage,
                    $total,
                    $requestedPage,
                    true,
                ),
            );
        }

        $idsResult = $elementClass::getList([
            'select' => ['ID'],
            'filter' => $filter,
            'order' => $order,
            'limit' => $request->perPage,
            'offset' => ($requestedPage - 1) * $request->perPage,
        ]);

        $ids = array_map('intval', array_column($idsResult->fetchAll(), 'ID'));

        return new ArticlePage(
            $this->fetchByIds($ids),
            new Pagination(
                $requestedPage,
                $pageCount,
                $request->perPage,
                $total,
                $requestedPage,
                false,
            ),
        );
    }

    public function findByCode(string $code): ?Article
    {
        $elementClass = $this->iblocks->getElementClass();
        $filter = ArticleFilter::published();
        $filter['=CODE'] = $code;

        $row = $elementClass::getList([
            'select' => ['ID'],
            'filter' => $filter,
            'limit' => 1,
        ])->fetch();

        if (!$row)
        {
            return null;
        }

        return $this->fetchByIds([(int)$row['ID']])[0] ?? null;
    }

    /**
     * @return array<int, Article>
     */
    public function getReadNext(int $excludeId, int $limit = 3): array
    {
        if ($limit < 1)
        {
            return [];
        }

        $elementClass = $this->iblocks->getElementClass();
        $filter = ArticleFilter::published();
        $filter['!ID'] = $excludeId;
        $order = $this->listingOrder();

        $idsResult = $elementClass::getList([
            'select' => ['ID'],
            'filter' => $filter,
            'order' => $order,
            'limit' => $limit,
        ]);

        $ids = array_map('intval', array_column($idsResult->fetchAll(), 'ID'));

        return $this->fetchByIds($ids);
    }

    /**
     * @return array<int, int> ID рубрики => количество опубликованных статей
     */
    public function countsBySection(): array
    {
        $elementClass = $this->iblocks->getElementClass();
        $rows = $elementClass::getList([
            'select' => [
                'IBLOCK_SECTION_ID',
                new ExpressionField('CNT', 'COUNT(*)'),
            ],
            'filter' => ArticleFilter::published(),
            'group' => ['IBLOCK_SECTION_ID'],
        ])->fetchAll();

        $counts = [];

        foreach ($rows as $row)
        {
            $counts[(int)$row['IBLOCK_SECTION_ID']] = (int)$row['CNT'];
        }

        return $counts;
    }

    /**
     * @return array<string, string>
     */
    private function listingOrder(): array
    {
        return [
            'ACTIVE_FROM' => 'DESC',
            'SORT' => 'ASC',
            'ID' => 'DESC',
        ];
    }

    /**
     * Загружает элементы по списку ID.
     *
     * Сначала выбираются только ID, затем — полные данные одним запросом.
     * Это осознанный обход особенности ORM: при выборке multiple-свойств
     * вместе с limit результаты могут обрезаться. Не объединяйте шаги.
     *
     * @param array<int, int> $ids
     *
     * @return array<int, Article>
     */
    private function fetchByIds(array $ids): array
    {
        if ($ids === [])
        {
            return [];
        }

        $elementClass = $this->iblocks->getElementClass();
        $collection = $elementClass::getList([
            'select' => self::SELECT,
            'filter' => ['@ID' => $ids],
        ])->fetchCollection();

        $byId = [];

        foreach ($collection as $element)
        {
            $article = $this->mapElement($element);
            $byId[$article->id] = $article;
        }

        $articles = [];

        foreach ($ids as $id)
        {
            if (isset($byId[$id]))
            {
                $articles[] = $byId[$id];
            }
        }

        return $articles;
    }

    private function mapElement(object $element): Article
    {
        $activeFrom = $element->get('ACTIVE_FROM');

        return new Article(
            (int)$element->get('ID'),
            (string)$element->get('NAME'),
            (string)$element->get('CODE'),
            (int)$element->get('IBLOCK_SECTION_ID'),
            $activeFrom instanceof DateTime ? $activeFrom : null,
            trim((string)$element->get('PREVIEW_TEXT')),
            (string)$element->get('DETAIL_TEXT'),
            $this->propertyValue($element->get('AUTHOR')),
            $this->propertyValue($element->get('AUTHOR_ROLE')),
            $this->propertyValue($element->get('READING_TIME')),
            $this->propertyValues($element->get('ARTICLE_TAGS')),
            (int)$element->get('PREVIEW_PICTURE'),
            (int)$element->get('DETAIL_PICTURE'),
        );
    }

    private function propertyValue(mixed $property): string
    {
        if (!is_object($property))
        {
            return trim((string)$property);
        }

        return trim((string)$property->get('VALUE'));
    }

    /**
     * @return array<int, string>
     */
    private function propertyValues(mixed $property): array
    {
        if (!is_object($property) || !method_exists($property, 'getAll'))
        {
            return [];
        }

        $values = [];

        foreach ($property->getAll() as $item)
        {
            $value = is_object($item) ? trim((string)$item->get('VALUE')) : trim((string)$item);

            if ($value !== '')
            {
                $values[] = $value;
            }
        }

        return $values;
    }
}
