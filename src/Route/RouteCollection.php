<?php

namespace Choredo\Route;

use Psr\Http\Message\ServerRequestInterface;

class RouteCollection extends \League\Route\RouteCollection
{
    private $dispatcher;

    public function getDispatcher(ServerRequestInterface $request)
    {
        if (!$this->dispatcher){
            $this->dispatcher = parent::getDispatcher($request);
        }

        return $this->dispatcher;

    }
}