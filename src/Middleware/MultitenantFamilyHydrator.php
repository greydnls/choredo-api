<?php


namespace Choredo\Middleware;


use Choredo\Entities\Family;
use Choredo\Exception\InvalidRequestException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MultiTenantFamilyHydrator
{
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $uriParts = explode('/', trim($request->getUri()->getPath(), '/'));

        $prefix = array_shift($uriParts);

        if ($prefix !== 'families'){
            return $next($request, $response);
        }

        $familyId = array_shift($uriParts);

        $family = $this->repository->findOneBy(['id' => $familyId]);

        if ($family == null){
            throw new InvalidRequestException("Invalid or non-existent family requested");
        }

        $request = $request->withAttribute('family', $family);

        return $next($request, $response);
    }
}