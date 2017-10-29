<?php

namespace Choredo\Test;

use Choredo\Exception\InvalidRequestException;
use Choredo\Middleware\MultiTenantFamilyHydrator;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Uri;

class MultiTenantFamilyHydratorTest extends TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|EntityRepository
     */
    private $repository;

    public function setUp()
    {
        $this->repository = $this->createMock(EntityRepository::class);
    }

    public function testUrisOutsideFamilyDomainDontThrowException()
    {
        $uri = $this->createMock(Uri::class);
        $uri->expects($this->once())
            ->method('getPath')
            ->willReturn('/fizzpuzz/1234');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $response = new Response();

        $this->constructSubjuect()($request, $response, function(){});

    }

    public function testInvalidFamilyIdThrowsInvalidRequestException()
    {
        $uri = $this->createMock(Uri::class);
        $uri->expects($this->once())
            ->method('getPath')
            ->willReturn('/families/1234');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $response = new Response();

        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('Invalid or non-existent family requested');

        $this->constructSubjuect()($request, $response, function(){});
    }

    public function constructSubjuect()
    {
        return new MultiTenantFamilyHydrator($this->repository);
    }
}
