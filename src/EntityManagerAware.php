<?php

namespace Choredo;

use Doctrine\ORM\EntityManagerInterface;

interface EntityManagerAware
{
    public function setEntityManager(EntityManagerInterface $entityManager): void;
}
