<?php

namespace Choredo\Middleware;

use Assert\Assertion;
use Choredo\Hydrators\Hydrator;
use Choredo\JsonApi\JsonApiResource;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ResourceHydrator
{
    /**
     * @var Hydrator
     */
    private $hydrator;

    public function __construct(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        /** @var JsonApiResource $resource */
        $resource = $request->getAttribute('resource');
        Assertion::isInstanceOf($request->getAttribute('resource'), JsonApiResource::class);

        $request = $request->withAttribute(
            $this->hydrator->getAttributeName(),
            $this->hydrator->hydrate($resource)
        );

        return $next($request, $response);
    }
}