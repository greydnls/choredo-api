<?php

namespace Choredo\Middleware;

use Assert\Assert;
use Assert\Assertion;
use Choredo\Hydrators\Hydrator;
use Choredo\JsonApi\JsonApiResource;
use Choredo\JsonApi\Resource;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;

class ResourceHydrator
{
    const TYPE_UUID = 'uuid';
    const TYPE_NEW = 'new';

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
        return new static($expectedResourceType, self::TYPE_NEW, $hydrator);
    }

    public static function uuidType(string $expectedResourceType, Hydrator $hydrator = null)
    {
        return new static($expectedResourceType, self::TYPE_UUID, $hydrator);
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $body = $request->getBody()->getContents();

        Assertion::isJsonString($body);

        $body = json_decode($body, true);

        Assert::that($body)
            ->keyExists('data');

        $parsedBody = $body['data'];

        Assert::lazy()
            ->that($parsedBody, 'request::body')
            ->keyExists('attributes')
            ->keyExists('id')
            ->keyExists('type')
            ->that($parsedBody['attributes'], 'request::body::attributes')
            ->isArray()
            ->that($parsedBody['type'], 'request::body::type')
            ->eq($this->expectedType)
            ->verifyNow();

        if ($this->idType === self::TYPE_UUID) {
            Assert::lazy()
                ->that($parsedBody['id'], 'request::body::id')
                ->uuid()
                ->verifyNow();

            $parsedBody['id'] = Uuid::fromString($parsedBody['id']);
        } else {
            Assert::lazy()
                ->that($parsedBody['id'], 'request::body::id')
                ->eq(self::TYPE_NEW)
                ->verifyNow();
        }

        $resource = new Resource(
            $parsedBody['id'],
            $parsedBody['type'],
            $parsedBody['attributes']
        );

        if ($this->hydrator){
            $resource = $this->hydrator->hydrate($resource);
        }

        $request = $request->withAttribute('resource', $resource);

        return $next($request, $response);
    }
}