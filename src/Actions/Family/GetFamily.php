<?php

namespace Choredo\Actions\Family;

use Choredo\Entities;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAwareInterface;
use Choredo\Transformers;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response\JsonResponse;

class GetFamily implements FractalAwareInterface
{
    use CreatesFractalScope;

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $params = []
    ): ResponseInterface {
        $id = Uuid::fromString($params['id']);
        $family = new Entities\Family($id, 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHILD, 0);

        return new JsonResponse($this->outputItem($family, new Transformers\Family(), 'family')->toArray());
    }
}
