<?php

declare(strict_types=1);

namespace Choredo\Actions\Chore;

use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetChore implements FractalAware
{
    use CreatesFractalScope;

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $vars
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $vars
    ): ResponseInterface {
        return new JsonResponse(__CLASS__ . '::' . __FUNCTION__);
    }
}
