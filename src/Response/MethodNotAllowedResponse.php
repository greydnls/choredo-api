<?php

namespace Choredo\Response;

use Zend\Diactoros\Response\JsonResponse;

class MethodNotAllowedResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(
            [],
            405
        );
    }
}