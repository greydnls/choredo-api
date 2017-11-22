<?php

namespace Choredo\Actions\Family;

use Assert\Assert;
use Choredo\Entities\Family;
use Choredo\EntityManagerAwareInterface;
use Choredo\Filter;
use Choredo\Filterable;
use Choredo\HasEntityManager;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAwareInterface;
use Choredo\Pageable;
use Choredo\Pagination;
use Choredo\PaginationCriteria;
use Choredo\Repositories\FamilyRepository;
use Choredo\Sortable;
use Choredo\Transformers\FamilyTransformer;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\DAYS_OF_WEEK;
use const Choredo\REQUEST_FILTER;
use const Choredo\REQUEST_PAGINATION;
use const Choredo\REQUEST_SORT;

class ListFamilies implements FractalAwareInterface, EntityManagerAwareInterface, Pageable, Sortable, Filterable
{
    use CreatesFractalScope;
    use HasEntityManager;

    public static function getSortableFields(): array
    {
        return [
            'createdDate',
        ];
    }

    public static function getDefaultLimit(): int
    {
        return Pageable::DEFAULT_LIMIT;
    }

    public static function getMaxLimit(): int
    {
        return Pageable::DEFAULT_MAX_LIMIT;
    }

    public static function getDefaultSort(): array
    {
        return [
            ['createdDate', 'ASC'],
        ];
    }

    /**
     * Return an array of fields that can be filtered via the API
     *
     * @return array
     */
    public static function getFilterableFields(): array
    {
        return [
            'name'                => null,
            'paymentStrategy'     => null,
            'completionThreshold' => null,
            'weekStartDay'        => function ($value) {
                Assert::that($value)->inArray(DAYS_OF_WEEK);
                return array_search($value, DAYS_OF_WEEK);
            }
        ];
    }

    public function __invoke(Request $request, Response $response, array $vars): Response
    {
        /** @var Pagination $pagination */
        $pagination = $request->getAttribute(REQUEST_PAGINATION);

        /** @var array $sorts */
        $sorts = $request->getAttribute(REQUEST_SORT);

        /** @var Filter[] $filters */
        $filters = $request->getAttribute(REQUEST_FILTER, []);

        /** @var FamilyRepository $repository */
        $repository = $this->entityManager->getRepository(Family::class);

        /** @var Paginator $paginator */
        $paginator = $repository->getAll($pagination, $sorts, $filters);

        $collection = $this->outputCollection(
            $paginator,
            new FamilyTransformer(),
            Family::API_ENTITY_TYPE,
            '/families'
        );

        return new JsonResponse($collection->toArray());
    }
}
