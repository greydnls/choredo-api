<?php

declare(strict_types=1);

namespace Choredo;

use Doctrine\ORM\EntityManagerInterface;

interface EntityManagerAware
{
    public function setEntityManager(EntityManagerInterface $entityManager): void;
}
