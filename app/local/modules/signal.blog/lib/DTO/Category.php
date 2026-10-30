<?php

declare(strict_types=1);

namespace Signal\Blog\DTO;

/**
 * Рубрика блога.
 */
final readonly class Category
{
    public function __construct(
        public int $id,
        public string $name,
        public string $code,
        public string $description = '',
        public int $count = 0,
    ) {
    }

    public static function unknown(): self
    {
        return new self(0, '', '', '', 0);
    }

    public function withCount(int $count): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->code,
            $this->description,
            $count
        );
    }
}
