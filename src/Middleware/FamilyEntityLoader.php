<?php

declare(strict_types=1);

namespace Choredo\Middleware;

use Doctrine\ORM\EntityRepository;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use const Choredo\REQUEST_FAMILY;

class FamilyEntityLoader
{
    private $repository;

    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $uriParts = explode('/', trim($request->getUri()->getPath(), '/'));

        $prefix = array_shift($uriParts);

        if ($prefix !== 'families') {
            return $next($request, $response);
        }

        $familyId = array_shift($uriParts);

        $family = $this->repository->findOneBy(['id' => $familyId]);

        if ($family == null) {
            throw new NotFoundException();
        }

        $request = $request->withAttribute(REQUEST_FAMILY, $family);

        return $next($request, $response);
    }
}
