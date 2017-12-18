<?php

declare(strict_types=1);

namespace Choredo;

use Choredo\Entities\Family;

interface FamilyAware
{
    /**
     * @return \Choredo\Entities\Family
     */
    public function getFamily(): Family;

    /**
     * @param \Choredo\Entities\Family $family
     */
    public function setFamily(Family $family): void;
}
