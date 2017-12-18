<?php

declare(strict_types=1);

namespace Choredo\Repositories;

use Choredo\LimitOffset;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class StandardQueryModifier
{
    public static function apply(
        QueryBuilder $queryBuilder,
        LimitOffset $pagination,
        array $sorts = [],
        array $filters = []
    ): Paginator {
        $rootAlias = array_pop($queryBuilder->getRootAliases());
        foreach ($filters as $filter) {
            $queryBuilder->andWhere(
                $rootAlias . ".{$filter->getField()} = :{$filter->getField()}"
            )->setParameter($filter->getField(), $filter->getValue());
        }

        foreach ($sorts as $sort) {
            $queryBuilder->orderBy(
                $rootAlias . ".{$sort->getField()}",
                $sort->getDirection()
            );
        }

        $paginator = new Paginator($queryBuilder);
        $paginator->getQuery()
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getLimit());

        return $paginator;
    }
}
