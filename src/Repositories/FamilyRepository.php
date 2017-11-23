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
        $queryBuilder = $this->createQueryBuilder('families');
        foreach ($filters as $filter) {
            $queryBuilder->andWhere("families.{$filter->getField()} = :{$filter->getField()}")
                ->setParameter($filter->getField(), $filter->getValue());
        }
        foreach ($sorts as $sort) {
            $queryBuilder->orderBy("families.{$sort->getField()}", $sort->getDirection());
        }

        $paginator = new Paginator($queryBuilder);
        $paginator->getQuery()->setFirstResult($pagination->getOffset())->setMaxResults($pagination->getLimit());

        return $paginator;
    }
}
