<?php

namespace Choredo\Middleware;

use Assert\Assertion;
use Choredo\Pageable;
use Choredo\PaginationCriteria;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use const Choredo\REQUEST_HANDLER_CLASS;

class PaginationParser
{
    /**
     * @var int
     */
    private $defaultLimit;
    /**
     * @var int
     */
    private $defaultOffset;
    /**
     * @var int
     */
    private $maxLimit;

    /**
     * PaginationParser constructor.
     *
     * @param int $defaultLimit
     * @param int $defaultOffset
     * @param int $maxLimit
     */
    public function __construct(int $defaultLimit = 10, int $defaultOffset = 0, int $maxLimit = 100)
    {
        $this->defaultLimit = $defaultLimit;
        $this->defaultOffset = $defaultOffset;
        $this->maxLimit = $maxLimit;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $handler = $request->getAttribute(REQUEST_HANDLER_CLASS);
        if (!in_array(Pageable::class, class_implements($handler))) {
            return $next($request, $response);
        };

        $params = $request->getQueryParams();
        $limit = $params['limit'] ?? $this->defaultLimit;
        $offset = $params['offset'] ?? $this->defaultOffset;

        Assertion::lessOrEqualThan($limit, $this->maxLimit);

        $request = $request->withAttribute('paginationCriteria', new PaginationCriteria($limit, $offset));
        return $next($request, $response);
    }
}
