<?php

namespace Choredo\Response;

use Zend\Diactoros\Response\JsonResponse;

class NotFoundResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(
            [],
            404,
            ['Access-Control-Allow-Origin' => '*']
        );
    }
}