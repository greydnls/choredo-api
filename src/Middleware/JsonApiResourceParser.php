<?php


namespace Choredo\Middleware;


use Assert\Assert;
use Assert\Assertion;
use Choredo\JsonApiResource;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;

class JsonApiResourceParser
{
    const TYPE_UUID = 'uuid';
    const TYPE_NEW = 'new';

    /**
     * @var string
     */
    private $idType;
    /**
     * @var string
     */
    private $expectedType;

    private function __construct(string $expectedType, string $idType)
    {
        $this->idType = $idType;
        $this->expectedType = $expectedType;
    }

    public static function newType(string $expectedResourceType)
    {
        return new static($expectedResourceType, self::TYPE_NEW);
    }

    public static function uuidType(string $expectedResourceType)
    {
        return new static($expectedResourceType, self::TYPE_UUID);
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $body = $request->getBody()->getContents();

        Assertion::isJsonString($body);

        $parsedBody = json_decode($body, true);

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

        if ($this->idType === self::TYPE_UUID){
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

        $request = $request->withAttribute('resource', new JsonApiResource(
            $parsedBody['id'],
            $parsedBody['type'],
            $parsedBody['attributes']
        ));

        return $next($request, $response);
    }
}