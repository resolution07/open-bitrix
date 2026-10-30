<?php

declare(strict_types=1);

namespace Signal\Blog\Infrastructure;

use Signal\Blog\Exception\IblockNotConfiguredException;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\Model\Section;
use Bitrix\Main\Loader;

/**
 * Доступ к ORM-сущностям инфоблока блога.
 *
 * Классическое API здесь используется только для подключения модуля.
 * Все выборки выполняются через скомпилированные D7-сущности.
 */
final class IblockEntityProvider
{
    private const API_CODE = 'Blog';

    private ?string $elementClass = null;
    private ?string $sectionClass = null;

    public function __construct()
    {
        if (!Loader::includeModule('iblock'))
        {
            throw new IblockNotConfiguredException('Не удалось подключить модуль iblock.');
        }
    }

    public function getElementClass(): string
    {
        if ($this->elementClass === null)
        {
            $entity = IblockTable::compileEntity(self::API_CODE);

            if (!$entity)
            {
                throw new IblockNotConfiguredException(
                    'Не удалось скомпилировать ORM-сущность инфоблока ' . self::API_CODE . '.'
                );
            }

            $this->elementClass = (string)$entity->getDataClass();
        }

        return $this->elementClass;
    }

    public function getSectionClass(): string
    {
        if ($this->sectionClass === null)
        {
            $sectionClass = (string)(Section::compileEntityByIblock(self::API_CODE) ?? '');

            if ($sectionClass === '')
            {
                throw new IblockNotConfiguredException(
                    'Не удалось скомпилировать ORM-сущность разделов инфоблока ' . self::API_CODE . '.'
                );
            }

            $this->sectionClass = $sectionClass;
        }

        return $this->sectionClass;
    }
}
