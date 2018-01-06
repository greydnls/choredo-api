<?php

declare(strict_types=1);

namespace Choredo\Actions\Chore;

use Choredo\Actions\Behaviors\Filterable;
use Choredo\Actions\Behaviors\HasDefaultCreateDateSort;
use Choredo\Actions\Behaviors\HasDefaultPaginationLimits;
use Choredo\Actions\Behaviors\HasNoFilterTransforms;
use Choredo\Actions\Behaviors\Pageable;
use Choredo\Actions\Behaviors\Sortable;
use Choredo\Entities\Chore;
use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Choredo\Transformers\ChoreTransformer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\REQUEST_FAMILY;
use const Choredo\REQUEST_FILTER;
use const Choredo\REQUEST_PAGINATION;
use const Choredo\REQUEST_SORT;

class ListChores implements FractalAware, EntityManagerAware, Pageable, Sortable, Filterable
{
    use CreatesFractalScope;
    use HasEntityManager;
    use HasDefaultPaginationLimits;
    use HasDefaultCreateDateSort;
    use HasNoFilterTransforms;

    public function __invoke(Request $request, Response $response, array $params = []): Response
    {
        $pagination = $request->getAttribute(REQUEST_PAGINATION);
        $sorts      = $request->getAttribute(REQUEST_SORT, []);
        $filters    = $request->getAttribute(REQUEST_FILTER, []);
        $family     = $request->getAttribute(REQUEST_FAMILY);

        /** @var \Choredo\Repositories\ChoreRepository $repository */
        $repository = $this->entityManager->getRepository(Chore::class);

        /** @var \Doctrine\ORM\Tools\Pagination\Paginator $paginator */
        $paginator = $repository->getAll($pagination, $sorts, $filters);

        $collection = $this->outputCollection(
            $paginator,
            new ChoreTransformer(),
            Chore::API_ENTITY_TYPE,
            "/families/{$family->getId()}/chores"
        );

        return new JsonResponse($collection->toArray());
    }

    /**
     * Return an array of fields that can be filtered via the API.
     *
     * @return string[]
     */
    public static function getFilterableFields(): array
    {
        return ['name'];
    }

    /**
     * @return string[]
     */
    public static function getSortableFields(): array
    {
        return ['name', 'createdDate', 'updatedDate'];
    }
}
