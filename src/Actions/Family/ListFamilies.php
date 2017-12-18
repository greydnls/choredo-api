<?php

declare(strict_types=1);

namespace Choredo\Actions\Family;

use Assert\Assert;
use Choredo\Actions\Behaviors;
use Choredo\Entities\Family;
use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output;
use Choredo\Transformers\FamilyTransformer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\DAYS_OF_WEEK;
use const Choredo\REQUEST_FILTER;
use const Choredo\REQUEST_PAGINATION;
use const Choredo\REQUEST_SORT;

class ListFamilies implements EntityManagerAware, Output\FractalAware, Behaviors\Filterable, Behaviors\Pageable, Behaviors\Sortable
{
    use Output\CreatesFractalScope;
    use HasEntityManager;
    use Behaviors\HasDefaultCreateDateSort;
    use Behaviors\HasDefaultPaginationLimits;

    public function __invoke(Request $request, Response $response, array $vars): Response
    {
        /** @var \Choredo\LimitOffset $pagination */
        $pagination = $request->getAttribute(REQUEST_PAGINATION);

        /** @var \Choredo\Sort[] $sorts */
        $sorts = $request->getAttribute(REQUEST_SORT, []);

        /** @var \Choredo\Filter[] $filters */
        $filters = $request->getAttribute(REQUEST_FILTER, []);

        /** @var \Choredo\Repositories\FamilyRepository $repository */
        $repository = $this->entityManager->getRepository(Family::class);

        /** @var \Doctrine\ORM\Tools\Pagination\Paginator $paginator */
        $paginator = $repository->getAll($pagination, $sorts, $filters);

        $collection = $this->outputCollection(
            $paginator,
            new FamilyTransformer(),
            Family::API_ENTITY_TYPE,
            '/families'
        );

        return new JsonResponse($collection->toArray());
    }

    /**
     * @return string[]
     */
    public static function getSortableFields(): array
    {
        return ['createdDate'];
    }

    /**
     * Return an array of fields that can be filtered via the API.
     *
     * @return string[]
     */
    public static function getFilterableFields(): array
    {
        return ['name', 'paymentStrategy', 'completionThreshold', 'weekStartDay'];
    }

    /**
     * @return array {
     *
     * @var string   $field Name of the field which requires a transform
     * @var callable $callable A callable transform function `function ($value) {}`
     *               }
     */
    public static function getFilterTransforms(): array
    {
        return [
            'weekStartDay' => function ($value) {
                Assert::that($value)->inArray(DAYS_OF_WEEK);

                return array_search($value, DAYS_OF_WEEK, true);
            },
        ];
    }
}
