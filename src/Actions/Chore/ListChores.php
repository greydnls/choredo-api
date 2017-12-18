<?php

declare(strict_types=1);

namespace Choredo\Actions\Chore;

use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Choredo\Transformers\ChoreTransformer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class ListChores implements FractalAware
{
    use CreatesFractalScope;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $vars): ResponseInterface
    {
        return new JsonResponse($this->outputCollection([], new ChoreTransformer(), 'chores'));
    }
}
