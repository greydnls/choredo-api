<?php

declare(strict_types=1);

namespace Choredo\Entities\Behaviors;

use Choredo\Entities\Family;

trait BelongsToFamily
{
    /**
     * @ORM\ManyToOne(targetEntity="Choredo\Entities\Family")
     * @ORM\JoinColumn(name="family_id", referencedColumnName="id", nullable=false)
     *
     * @var Family
     */
    private $family;

    /**
     * @return mixed
     */
    public function getFamily(): Family
    {
        return $this->family;
    }

    /**
     * @param Family $family
     */
    public function setFamily(Family $family)
    {
        $this->family = $family;
    }
}
