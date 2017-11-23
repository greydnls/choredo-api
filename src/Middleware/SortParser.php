<?php

namespace Choredo\Middleware;

use Assert\Assert;
use Choredo\Sort;
use Choredo\Actions\Behaviors\Sortable;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use const Choredo\REQUEST_HANDLER_CLASS;
use const Choredo\REQUEST_SORT;

class SortParser
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        /** @var Sortable $handler */
        $handler = $request->getAttribute(REQUEST_HANDLER_CLASS);
        if (!in_array(Sortable::class, class_implements($handler))) {
            return $next($request, $response);
        };

        $sort = $request->getQueryParams()[REQUEST_SORT] ?? null;
        $sortParameters = $this->parseSortParameters($sort, $handler::getSortableFields());

        if (empty($sortParameters)) {
            $sortParameters = $handler::getDefaultSort();
        }

        $request = $request->withAttribute(REQUEST_SORT, $sortParameters);

        return $next($request, $response);
    }

    /**
     * @param string $sort
     * @return Sort[]
     */
    private function parseSortParameters(string $sort = null, array $allowedFields): array
    {
        if (empty($sort)) {
            return [];
        }

        $fields = explode(',', $sort);

        return array_map(
            function ($field) use ($allowedFields) {
                if (substr($field, 0, 1) === '-') {
                    $field = substr($field, 1);
                    Assert::that($field)->inArray($allowedFields);
                    return new Sort($field, Sort::DIRECTION_DESCENDING);
                }

                Assert::that($field)->inArray($allowedFields);
                return new Sort($field, Sort::DIRECTION_ASCENDING);
            },
            $fields
        );
    }
}
