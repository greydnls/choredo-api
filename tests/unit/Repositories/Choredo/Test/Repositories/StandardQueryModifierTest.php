<?php


namespace Choredo\Test\Repositories;


use Choredo\Actions\Behaviors\Sortable;
use Choredo\Filter;
use Choredo\LimitOffset;
use Choredo\Repositories\StandardQueryModifier;
use Choredo\Sort;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class StandardQueryModifierTest extends TestCase
{
    public function testAppliesPagination()
    {
        $qb = $this->createMock(QueryBuilder::class);
        $qb->expects($this->once())
            ->method('getRootAliases')
            ->willReturn(['u']);

        $qb->expects($this->once())
            ->method('getQuery')
            ->willReturn($this->getQuery());

        $pagination = $this->createMock(LimitOffset::class);

        $pagination->expects($this->once())
            ->method('getOffset');
        $pagination->expects($this->once())
            ->method('getLimit');

        StandardQueryModifier::apply($qb, $pagination);
    }

    public function testAppliesSorts()
    {
        $qb = $this->createMock(QueryBuilder::class);
        $qb->expects($this->once())
            ->method('getRootAliases')
            ->willReturn(['u']);

        $qb->expects($this->once())
            ->method('getQuery')
            ->willReturn($this->getQuery());

        $qb->expects($this->once())
            ->method('orderBy')
            ->with('u.name', 'ASC');

        $pagination = $this->createMock(LimitOffset::class);

        $sorts = [
            new Sort('name', Sort::DIRECTION_ASCENDING)
        ];

        StandardQueryModifier::apply($qb, $pagination, $sorts);
    }

    public function testAppliesFilters()
    {
        $qb = $this->createMock(QueryBuilder::class);
        $qb->expects($this->once())
            ->method('getRootAliases')
            ->willReturn(['u']);

        $qb->expects($this->once())
            ->method('getQuery')
            ->willReturn($this->getQuery());

        $qb->expects($this->once())
            ->method('andWhere')
            ->with('u.name = :name')
            ->will($this->returnSelf());

        $qb->expects($this->once())
            ->method('setParameter')
            ->with('name', 'Bob');

        $pagination = $this->createMock(LimitOffset::class);

        $filters = [
            new Filter('name', 'Bob')
        ];

        StandardQueryModifier::apply($qb, $pagination, [], $filters);
    }

    private function getQuery()
    {
        return new class extends AbstractQuery {
            public function __construct(){}
            public function setFirstResult(int $result) {
                return $this;
            }
            public function setMaxResults(int $result) {
                return $this;
            }
            public function getSQL(){}
            protected function _doExecute() {}
        };
    }
}
