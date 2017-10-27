<?php


namespace Choredo\Http\Chores;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DeleteChore
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $vars) : ResponseInterface
    {

    }
}