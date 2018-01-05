<?php

declare(strict_types=1);

namespace Choredo\Actions\Child;

use Choredo\Actions\Behaviors\Filterable;
use Choredo\Actions\Behaviors\HasDefaultCreateDateSort;
use Choredo\Actions\Behaviors\HasDefaultPaginationLimits;
use Choredo\Actions\Behaviors\HasNoFilterTransforms;
use Choredo\Actions\Behaviors\Pageable;
use Choredo\Actions\Behaviors\Sortable;
use Choredo\Entities\Child;
use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Choredo\Transformers\ChildTransformer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\REQUEST_FAMILY;
use const Choredo\REQUEST_FILTER;
use const Choredo\REQUEST_PAGINATION;
use const Choredo\REQUEST_SORT;

class ListChildren implements EntityManagerAware, FractalAware, Pageable, Sortable, Filterable
{
    use HasEntityManager;
    use CreatesFractalScope;
    use HasDefaultPaginationLimits;
    use HasDefaultCreateDateSort;
    use HasNoFilterTransforms;

    /**
     * Return an array of fields that can be filtered via the API.
     *
     * @return string[]
     */
    public static function getFilterableFields(): array
    {
        return ['name', 'accessCode'];
    }

    /**
     * @return string[]
     */
    public static function getSortableFields(): array
    {
        return ['name', 'createdDate', 'updatedDate'];
    }

    public function __invoke(Request $request, Response $response, array $params = []): Response
    {
        $pagination = $request->getAttribute(REQUEST_PAGINATION);
        $sorts      = $request->getAttribute(REQUEST_SORT, []);
        $filters    = $request->getAttribute(REQUEST_FILTER, []);
        $family     = $request->getAttribute(REQUEST_FAMILY);

        /** @var \Choredo\Repositories\ChildRepository $repository */
        $repository = $this->entityManager->getRepository(Child::class);

        /** @var \Doctrine\ORM\Tools\Pagination\Paginator $paginator */
        $paginator = $repository->getAll($pagination, $sorts, $filters);

        $collection = $this->outputCollection(
            $paginator,
            new ChildTransformer(),
            Child::API_ENTITY_TYPE,
            "/families/{$family->getId()}/children"
        );

        return new JsonResponse($collection->toArray());
    }
}
