<?php

declare(strict_types=1);

namespace Choredo\Actions\Account;

use Choredo\Actions\Behaviors;
use Choredo\Entities\Account;
use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output;
use Choredo\Transformers\AccountTransformer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\REQUEST_FAMILY;
use const Choredo\REQUEST_FILTER;
use const Choredo\REQUEST_PAGINATION;
use const Choredo\REQUEST_SORT;

class ListAccounts implements EntityManagerAware, Output\FractalAwareInterface, Behaviors\Filterable, Behaviors\Pageable, Behaviors\Sortable
{
    use Output\CreatesFractalScope;
    use HasEntityManager;
    use Behaviors\HasDefaultCreateDateSort;
    use Behaviors\HasDefaultPaginationLimits;
    use Behaviors\HasNoFilterTransforms;

    public function __invoke(Request $request, Response $response, array $vars): Response
    {
        /** @var \Choredo\LimitOffset $pagination */
        $pagination = $request->getAttribute(REQUEST_PAGINATION);

        /** @var \Choredo\Sort[] $sorts */
        $sorts = $request->getAttribute(REQUEST_SORT, []);

        /** @var \Choredo\Filter[] $filters */
        $filters = $request->getAttribute(REQUEST_FILTER, []);

        /** @var \Choredo\Repositories\FamilyRepository $repository */
        $repository = $this->entityManager->getRepository(Account::class);

        /** @var \Doctrine\ORM\Tools\Pagination\Paginator $paginator */
        $paginator = $repository->getAll($pagination, $sorts, $filters);

        /** @var \Choredo\Entities\Family $family */
        $family = $request->getAttribute(REQUEST_FAMILY);

        $collection = $this->outputCollection(
            $paginator,
            new AccountTransformer(),
            Account::API_ENTITY_TYPE,
            "/families/{$family->getId()}/"
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
        return ['emailAddress', 'firstName', 'lastName'];
    }

    /**
     * @return string[]
     */
    public static function getSortableFields(): array
    {
        return ['createdDate', 'lastLogin', 'firstName', 'lastName'];
    }
}
