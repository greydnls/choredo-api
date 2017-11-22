<?php

namespace Choredo\Middleware;

use Assert\Assert;
use Choredo\Sortable;
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
        if (is_null($sort)) {
            return $next($request->withAttribute(REQUEST_SORT, $handler::getDefaultSort()), $response);
        }

        $sortParameters = $this->parseSortParameters($sort);
        $sortFields = array_column($sortParameters, 0);
        $allowedFields = $handler::getSortableFields();
        Assert::lazy()
            ->that(
                count(array_diff($sortFields, $allowedFields)),
                REQUEST_SORT,
                'Cannot sort by unsortable or unknown field'
            )->eq(0)
            ->verifyNow();

        if (empty($sortParameters)) {
            $sortParameters = $handler::getDefaultSort();
        }
        $request = $request->withAttribute(REQUEST_SORT, $sortParameters);

        return $next($request, $response);
    }

    private function parseSortParameters(string $sort): array
    {
        if (empty($sort)) {
            return [];
        }

        $fields = explode(',', $sort);

        return array_map(
            function ($field) {
                if (substr($field, 0, 1) === '-') {
                    return [substr($field, 1), 'DESC'];
                }

                return [$field, 'ASC'];
            },
            $fields
        );
    }
}
