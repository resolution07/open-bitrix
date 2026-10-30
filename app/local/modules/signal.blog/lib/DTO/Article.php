<?php

declare(strict_types=1);

namespace Signal\Blog\DTO;

use Bitrix\Main\Type\DateTime;

/**
 * Данные статьи инфоблока без привязки к представлению.
 */
final readonly class Article
{
    /**
     * @param array<int, string> $tags
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $code,
        public int $sectionId,
        public ?DateTime $activeFrom,
        public string $previewText,
        public string $detailText,
        public string $author,
        public string $authorRole,
        public string $readingTime,
        public array $tags,
        public int $previewPictureId,
        public int $detailPictureId,
    ) {
    }

    public function cardPictureId(): int
    {
        return $this->previewPictureId > 0
            ? $this->previewPictureId
            : $this->detailPictureId;
    }

    public function coverPictureId(): int
    {
        return $this->detailPictureId > 0
            ? $this->detailPictureId
            : $this->previewPictureId;
    }
}
