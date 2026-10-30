<?php

declare(strict_types=1);

namespace Signal\Blog\Presenter;

use Signal\Blog\DTO\Category;
use Signal\Blog\Url;

/**
 * Представление рубрики для шаблона.
 */
final class CategoryPresenter
{
    /**
     * @return array{id:int,name:string,code:string,url:string,count:int}
     */
    public function toArray(Category $category): array
    {
        $hasCode = $category->code !== '';

        return [
            'id' => $category->id,
            'name' => $category->name !== '' ? $category->name : 'Без рубрики',
            'code' => $category->code,
            'url' => $hasCode ? Url::category($category->code) : Url::blog(),
            'count' => $category->count,
        ];
    }
}
