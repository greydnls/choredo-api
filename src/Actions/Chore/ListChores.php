<?php

namespace Choredo\Actions\Chore;

use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAwareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Choredo\Transformer\ChoreTransformer;
use Zend\Diactoros\Response\JsonResponse;

class ListChores implements FractalAwareInterface
{
    use CreatesFractalScope;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $vars) : ResponseInterface
    {
        return new JsonResponse($this->outputCollection([], new ChoreTransformer(), 'chores'));
    }
}