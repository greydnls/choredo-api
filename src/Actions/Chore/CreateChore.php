<?php


namespace Choredo\Actions\Chore;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class CreateChore
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $vars
    ) : ResponseInterface {
        return new JsonResponse(__CLASS__."::".__FUNCTION__);
    }
}
