<?php

namespace Choredo\Actions\Family;

use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAwareInterface;
use Choredo\Transformers\FamilyTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class CreateFamily implements FractalAwareInterface
{
    use CreatesFractalScope;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CreateFamily constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $family = $request->getAttribute('familyEntity');

        $this->entityManager->persist($family);
        $this->entityManager->flush();

        $item = $this->outputItem($family, new FamilyTransformer(), 'families')->toArray();
        return (new JsonResponse($item))->withHeader("location", "/families/" . $family->getId());
    }
}
