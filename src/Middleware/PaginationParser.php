<?php

declare(strict_types=1);

namespace Choredo\Middleware;

use Assert\Assert;
use Choredo\Actions\Behaviors\Pageable;
use Choredo\LimitOffset;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use const Choredo\REQUEST_HANDLER_CLASS;
use const Choredo\REQUEST_PAGINATION;

class PaginationParser
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        /** @var Pageable $handler */
        $handler = $request->getAttribute(REQUEST_HANDLER_CLASS);
        if (!in_array(Pageable::class, class_implements($handler), true)) {
            return $next($request, $response);
        }

        $page = array_merge(
            ['limit' => $handler::getDefaultLimit(), 'offset' => Pageable::DEFAULT_OFFSET],
            $request->getQueryParams()['page'] ?? []
        );

        Assert::lazy()
            ->that($page['limit'], 'page[limit]')->numeric()->between(1, $handler::getMaxLimit())
            ->that($page['offset'], 'page[offset]')->numeric()->greaterOrEqualThan(0)
            ->verifyNow();

        $request = $request->withAttribute(REQUEST_PAGINATION, new LimitOffset($page['limit'], $page['offset']));

        return $next($request, $response);
    }
}
