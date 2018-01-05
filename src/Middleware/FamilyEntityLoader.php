<?php

declare(strict_types=1);

namespace Choredo\Middleware;

use Choredo\Entities\Family;
use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use const Choredo\REQUEST_FAMILY;

class FamilyEntityLoader implements EntityManagerAware
{
    use HasEntityManager;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $uriParts = explode('/', trim($request->getUri()->getPath(), '/'));

        $prefix = array_shift($uriParts);

        if ($prefix !== 'families') {
            return $next($request, $response);
        }

        $familyId = array_shift($uriParts);

        $repository = $this->entityManager->getRepository(Family::class);
        $family     = $repository->findOneBy(['id' => $familyId]);

        if ($family == null) {
            throw new NotFoundException();
        }

        $this->entityManager->getFilters()->enable('family')->setParameter('familyId', $family->getId()->toString());

        $request = $request->withAttribute(REQUEST_FAMILY, $family);

        return $next($request, $response);
    }
}
