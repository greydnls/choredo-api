<?php


namespace Choredo\Actions\Chore;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DeleteChore
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $vars) : ResponseInterface
    {
    }
}
