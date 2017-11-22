<?php

namespace Choredo;

use Doctrine\ORM\EntityManagerInterface;

trait HasEntityManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }
}
