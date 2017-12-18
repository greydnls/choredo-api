<?php

declare(strict_types=1);

namespace Choredo\Test;

use Choredo\Middleware\FamilyEntityLoader;
use Doctrine\ORM\EntityRepository;
use League\Route\Http\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Uri;

class FamilyEntityLoaderTest extends TestCase
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

        $this->constructSubject()($request, $response, function () {});
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

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Not Found');

        $this->constructSubject()($request, $response, function () {});
    }

    public function constructSubject()
    {
        return new FamilyEntityLoader($this->repository);
    }
}
