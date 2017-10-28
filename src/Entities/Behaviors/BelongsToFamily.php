<?php

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
    public function getFamily()
    {
        return $this->family;
    }
}