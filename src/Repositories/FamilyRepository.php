<?php

namespace Choredo\Repositories;

use Choredo\Filter;
use Choredo\LimitOffset;
use Choredo\Sort;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class FamilyRepository
 * @package Choredo\Repositories
 */
class FamilyRepository extends EntityRepository
{
    /**
     * @param LimitOffset $pagination
     * @param Sort[] $sorts
     * @param Filter[] $filters
     * @return Paginator
     */
    public function getAll(LimitOffset $pagination, array $sorts = [], array $filters = []): Paginator
    {
        return StandardQueryModifier::apply(
            $this->createQueryBuilder('families'),
            $pagination,
            $sorts,
            $filters
        );
    }
}