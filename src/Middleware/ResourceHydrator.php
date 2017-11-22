<?php

namespace Choredo\Middleware;

use Assert\Assertion;
use Choredo\Hydrators\Hydrator;
use Choredo\JsonApi\JsonApiResource;
use const Choredo\REQUEST_RESOURCE;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ResourceHydrator
{
    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @var string
     */
    private $idType;
    /**
     * @var string
     */
    private $expectedType;

    private function __construct(string $expectedType, string $idType, Hydrator $hydrator = null)
    {
        $this->idType = $idType;
        $this->expectedType = $expectedType;
    }

    public static function newType(string $expectedResourceType, Hydrator $hydrator = null)
    {
        return new static($expectedResourceType, JsonApiResource::TYPE_NEW, $hydrator);
    }

    public static function uuidType(string $expectedResourceType, Hydrator $hydrator = null)
    {
        return new static($expectedResourceType, JsonApiResource::TYPE_UUID, $hydrator);
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $body = $request->getBody()->getContents();

        Assertion::isJsonString($body);
        $body = json_decode($body, true);

        $resource = (new \Choredo\JsonApi\ResourceHydrator())
            ->hydrate($this->expectedType,
                $this->idType,
                $body
            );

        if ($this->hydrator) {
            $resource = $this->hydrator->hydrate($resource);
        }

        $request = $request->withAttribute(REQUEST_RESOURCE, $resource);

        return $next($request, $response);
    }
}