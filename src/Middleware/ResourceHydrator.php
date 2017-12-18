<?php

declare(strict_types=1);

namespace Choredo\Middleware;

use Assert\Assertion;
use Choredo\FamilyAware;
use Choredo\Hydrators\Hydrator;
use Choredo\JsonApi\JsonApiResource;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use const Choredo\REQUEST_FAMILY;
use const Choredo\REQUEST_RESOURCE;

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
        $this->idType       = $idType;
        $this->expectedType = $expectedType;
        $this->hydrator     = $hydrator;
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
            ->hydrate(
                $this->expectedType,
                $this->idType,
                $body
            );

        if ($this->hydrator) {
            if ($this->hydrator instanceof FamilyAware) {
                $family = $request->getAttribute(REQUEST_FAMILY);
                if (!$family) {
                    throw new \RuntimeException(
                        "Attempted to hydrate a FamilyAware hydrator but there is no Family entity registered. Check " .
                        "middlewares are running in the expected order!"
                    );
                }
                $this->hydrator->setFamily($request->getAttribute(REQUEST_FAMILY));
            }
            $resource = $this->hydrator->hydrate($resource);
        }

        $request = $request->withAttribute(REQUEST_RESOURCE, $resource);

        return $next($request, $response);
    }

    public static function newType(string $expectedResourceType, Hydrator $hydrator = null)
    {
        return new static($expectedResourceType, JsonApiResource::TYPE_NEW, $hydrator);
    }

    public static function uuidType(string $expectedResourceType, Hydrator $hydrator = null)
    {
        return new static($expectedResourceType, JsonApiResource::TYPE_UUID, $hydrator);
    }
}
