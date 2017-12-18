<?php

declare(strict_types=1);

namespace Choredo\Actions\Family;

use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Choredo\Transformers;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\REQUEST_FAMILY;

class GetFamily implements FractalAware
{
    use CreatesFractalScope;

    public function __invoke(Request $request, Response $response, array $params = []): Response
    {
        $family = $request->getAttribute(REQUEST_FAMILY);

        if (!$family) {
            throw new NotFoundException();
        }

        return new JsonResponse(
            $this->outputItem($family, new Transformers\FamilyTransformer(), 'families')->toArray()
        );
    }
}
