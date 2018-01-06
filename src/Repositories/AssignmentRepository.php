<?php

declare(strict_types=1);

namespace Choredo\Repositories;

use Choredo\Entities\Assignment;
use Choredo\Filter;
use Choredo\LimitOffset;
use Choredo\Sort;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class AccountRepository.
 */
class AssignmentRepository extends EntityRepository
{
    /**
     * @param LimitOffset $pagination
     * @param Sort[]      $sorts
     * @param Filter[]    $filters
     *
     * @return Paginator
     */
    public function getAll(LimitOffset $pagination, array $sorts = [], array $filters = []): Paginator
    {
        return StandardQueryModifier::apply(
            $this->createQueryBuilder(Assignment::API_ENTITY_TYPE),
            $pagination,
            $sorts,
            $filters
        );
    }
}
