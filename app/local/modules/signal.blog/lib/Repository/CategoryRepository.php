<?php

declare(strict_types=1);

namespace Signal\Blog\Repository;

use Signal\Blog\DTO\Category;
use Signal\Blog\Infrastructure\IblockEntityProvider;

/**
 * ORM-доступ к рубрикам блога.
 *
 * Репозиторий возвращает только данные раздела. Количество статей
 * добавляет прикладной слой: это агрегат по другому инфоблоку.
 */
final class CategoryRepository
{
    public function __construct(private readonly IblockEntityProvider $iblocks)
    {
    }

    /**
     * @return array<int, Category> ID рубрики => рубрика
     */
    public function findAll(): array
    {
        $sectionClass = $this->iblocks->getSectionClass();
        $rows = $sectionClass::getList([
            'select' => ['ID', 'NAME', 'CODE', 'DESCRIPTION'],
            'filter' => [
                '=ACTIVE' => 'Y',
                '=GLOBAL_ACTIVE' => 'Y',
            ],
            'order' => [
                'SORT' => 'ASC',
                'NAME' => 'ASC',
            ],
        ])->fetchAll();

        return $this->mapRows($rows);
    }

    /**
     * @param array<int, int> $ids
     *
     * @return array<int, Category> ID рубрики => рубрика
     */
    public function findByIds(array $ids): array
    {
        if ($ids === [])
        {
            return [];
        }

        $sectionClass = $this->iblocks->getSectionClass();
        $rows = $sectionClass::getList([
            'select' => ['ID', 'NAME', 'CODE', 'DESCRIPTION'],
            'filter' => [
                '@ID' => $ids,
                '=ACTIVE' => 'Y',
                '=GLOBAL_ACTIVE' => 'Y',
            ],
        ])->fetchAll();

        return $this->mapRows($rows);
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     *
     * @return array<int, Category>
     */
    private function mapRows(array $rows): array
    {
        $categories = [];

        foreach ($rows as $row)
        {
            $category = new Category(
                (int)$row['ID'],
                (string)$row['NAME'],
                (string)$row['CODE'],
                (string)($row['DESCRIPTION'] ?? ''),
            );

            $categories[$category->id] = $category;
        }

        return $categories;
    }
}
