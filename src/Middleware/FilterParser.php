<?php

namespace Choredo\Middleware;

use Assert\Assert;
use Choredo\Actions\Behaviors\Filterable;
use Choredo\Filter;
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

        $filter = $request->getQueryParams()[REQUEST_FILTER] ?? [];
        if (empty($filter)) {
            return $next($request, $response);
        }

        $filters = $this->parseFilter($filter, $handler::getFilterableFields(), $handler::getFilterTransforms());

        return $next($request->withAttribute(REQUEST_FILTER, $filters), $response);
    }

    private function parseFilter(array $filter, array $filterableFields, array $filterTransforms = []): array
    {
        // No filters should exist in the keys of the $filter hash that are not in the filterable fields array
        Assert::that(count(array_diff(array_keys($filter), $filterableFields)))->eq(0);

        $filters = [];
        foreach ($filter as $field => $value) {
            $transform = $filterTransforms[$field] ?? null;
            $filters[] = new Filter($field, $value, $transform);
        }

        return $filters;
    }
}
