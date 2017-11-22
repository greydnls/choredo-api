<?php

namespace Choredo\Middleware;

use Assert\Assert;
use Choredo\Filter;
use Choredo\Filterable;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use const Choredo\REQUEST_FILTER;
use const Choredo\REQUEST_HANDLER_CLASS;

class FilterParser
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        /** @var Filterable $handler */
        $handler = $request->getAttribute(REQUEST_HANDLER_CLASS);
        if (!in_array(Filterable::class, class_implements($handler))) {
            return $next($request, $response);
        };

        $filters = $this->parseFilter(
            $request->getQueryParams()[REQUEST_FILTER] ?? [],
            $handler::getFilterableFields()
        );

        if (empty($filters)) {
            return $next($request, $response);
        }

        return $next($request->withAttribute(REQUEST_FILTER, $filters), $response);
    }

    private function parseFilter(array $filter, array $filterableFields): array
    {
        $filters = [];
        foreach ($filter as $field => $value) {
            Assert::that($field)->inArray(array_keys($filterableFields));
            if (is_callable($filterableFields[$field])) {
                $filters[] = new Filter($field, $value, $filterableFields[$field]);
                continue;
            }

            $filters[] = new Filter($field, $value);
        }
        return $filters;
    }
}
