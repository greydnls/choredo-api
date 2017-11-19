<?php


namespace Choredo\Actions;


use Choredo\Response\BadRequestResponse;
use Choredo\Response\NotFoundResponse;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Auth
{
    private $repository;

    public function __construct(EntityRepository $accountRepository)
    {
        $this->repository = $accountRepository;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $data = json_decode($request->getBody()->getContents(), true);

        if (!$data || !isset($data['data'])){
            return new BadRequestResponse(['Invalid request body sent']);
        }

        $data = $data['data'];

        $user = $this->repository->findOneBy(['emailAddress' => $data['emailAddress']]);

        if ($user === null){
            return new NotFoundResponse();
        }
    }
}