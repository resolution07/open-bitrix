<?php

declare(strict_types=1);

namespace Signal\Blog\Controller;

use Signal\Blog\DTO\PaginationRequest;
use Signal\Blog\Presenter\CategoryPresenter;
use Signal\Blog\Presenter\PageMetadataPresenter;
use Signal\Blog\Presenter\PaginationPresenter;
use Signal\Blog\Service\ArticleService;
use Signal\Blog\Service\CategoryService;
use Signal\Blog\Url;
use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\Response\Render\View;

/**
 * Публичные страницы блога.
 *
 * Маршруты описаны в /local/routes/web.php. Контроллер разбирает запрос,
 * вызывает сервисы и выбирает представление. Заголовки, пагинацию и
 * view-модели готовят presenter-ы.
 */
final class BlogController extends Controller
{
    public function configureActions(): array
    {
        return [
            'index' => [
                '-prefilters' => [
                    Authentication::class,
                    Csrf::class,
                ],
            ],
            'detail' => [
                '-prefilters' => [
                    Authentication::class,
                    Csrf::class,
                ],
            ],
        ];
    }

    public function indexAction(string $section = '', string $page = ''): View
    {
        $categoryService = $this->service(CategoryService::class);
        $categoryPresenter = $this->service(CategoryPresenter::class);
        $articleService = $this->service(ArticleService::class);
        $paginationPresenter = $this->service(PaginationPresenter::class);
        $metadataPresenter = $this->service(PageMetadataPresenter::class);

        $categories = array_map(
            [$categoryPresenter, 'toArray'],
            $categoryService->getCategories()
        );

        $activeSectionDto = $section !== '' ? $categoryService->find($section) : null;

        if ($section !== '' && $activeSectionDto === null)
        {
            return $this->notFound('Рубрика не найдена', 'Такой рубрики в журнале нет.');
        }

        $request = Context::getCurrent()->getRequest();
        $searchQuery = $this->queryString($request->getQuery('q'), 100);
        $tagQuery = $this->queryString($request->getQuery('tag'), 100);
        $pagination = new PaginationRequest(
            $this->requestedPage($page),
            ArticleService::PER_PAGE
        );

        $metadata = $metadataPresenter->forListing(
            $activeSectionDto,
            $searchQuery,
            $tagQuery,
            $pagination->requestedPage()
        );

        $listing = $articleService->getListing(
            $activeSectionDto?->id,
            $searchQuery,
            $tagQuery,
            $pagination,
            withHero: $metadata['showHero']
        );

        if ($listing['pagination']->isOutOfRange)
        {
            return $this->notFound('Страница не найдена', 'Такой страницы в журнале нет.');
        }

        $activeSection = $activeSectionDto !== null
            ? $categoryPresenter->toArray($activeSectionDto)
            : null;
        $baseUrl = $activeSectionDto !== null && $activeSectionDto->code !== ''
            ? Url::category($activeSectionDto->code)
            : Url::blog();
        $query = $this->listingQuery($searchQuery, $tagQuery);
        $canonical = Url::absolute(
            $paginationPresenter->pageUrl($baseUrl, $query, $listing['pagination']->page)
        );

        global $APPLICATION;

        $APPLICATION->SetTitle($metadata['title']);
        $APPLICATION->SetPageProperty('description', $metadata['description']);
        $APPLICATION->SetPageProperty('canonical', $canonical);

        if ($metadata['noindex'])
        {
            $APPLICATION->SetPageProperty('robots', 'noindex, follow');
        }

        return $this->renderView('blog/index', [
            'categories' => $categories,
            'activeSection' => $activeSection,
            'searchQuery' => $searchQuery,
            'tagQuery' => $tagQuery,
            'pageTitle' => $metadata['title'],
            'eyebrow' => $metadata['eyebrow'],
            'description' => $metadata['description'],
            'hero' => $listing['hero'],
            'articles' => $listing['items'],
            'pagination' => $paginationPresenter->build(
                $listing['pagination'],
                $baseUrl,
                $query
            ),
        ]);
    }

    public function detailAction(string $code): View
    {
        $articleService = $this->service(ArticleService::class);
        $article = $articleService->getByCode($code);

        if ($article === null)
        {
            return $this->notFound('Страница не найдена', 'Материал не найден или был удалён.');
        }

        $shareUrl = Url::absolute($article['url']);

        global $APPLICATION;

        $APPLICATION->SetTitle($article['name']);
        $APPLICATION->SetPageProperty('description', 'Статья «' . $article['name'] . '» в журнале СИГНАЛ.');
        $APPLICATION->SetPageProperty('canonical', $shareUrl);

        return $this->renderView('blog/detail', [
            'article' => $article,
            'related' => $articleService->getReadNext($article['id'], 3),
            'shareUrl' => $shareUrl,
        ]);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    private function service(string $class): object
    {
        return ServiceLocator::getInstance()->get($class);
    }

    /**
     * Query-параметры, которые нужно сохранять в ссылках пагинации.
     *
     * Это белый список фильтров страницы. Новый фильтр добавляется здесь
     * и передаётся в presenter как обычный query-параметр.
     *
     * @return array<string, string>
     */
    private function listingQuery(string $search, string $tag): array
    {
        $query = [];

        if ($search !== '')
        {
            $query['q'] = $search;
        }

        if ($tag !== '')
        {
            $query['tag'] = $tag;
        }

        return $query;
    }

    private function queryString(mixed $value, int $maxLength): string
    {
        if (!is_string($value))
        {
            return '';
        }

        $value = preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $value);
        $value = trim($value ?? '');

        if ($value === '')
        {
            return '';
        }

        return mb_substr($value, 0, $maxLength);
    }

    private function requestedPage(mixed $value): int
    {
        if (!is_int($value) && !is_string($value))
        {
            return 1;
        }

        $page = (int)$value;

        return $page > 0 ? $page : 1;
    }

    private function notFound(string $title, string $description): View
    {
        global $APPLICATION;

        Context::getCurrent()->getResponse()->setStatus(404);
        $APPLICATION->SetTitle($title);
        $APPLICATION->SetPageProperty('description', $description);
        $APPLICATION->SetPageProperty('robots', 'noindex, follow');

        return $this->renderView('blog/404');
    }
}
