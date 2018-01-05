<?php

declare(strict_types=1);

namespace Choredo;

use Choredo\Entities\Family;

trait FamilyAwareTrait
{
    /** @var \Choredo\Entities\Family */
    private $family;

    /**
     * @return \Choredo\Entities\Family
     */
    public function getFamily(): Family
    {
        return $this->family;
    }

    /**
     * @param \Choredo\Entities\Family $family
     */
    public function setFamily(Family $family): void
    {
        $this->family = $family;
    }
}
