<?php

namespace Choredo\Entities\Behaviors;

trait HasUpdatedDate
{
    /**
     * @ORM\Column(
     *     type="datetime",
     *     name="updated",
     *     nullable=false
     * )
     *
     * @var \DateTime
     */
    private $updatedDate;

    /** @ORM\PrePersist */
    public function updateUpdatedDate()
    {
        $this->updatedDate = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getUpdateDate() :\DateTime
    {
        return $this->updatedDate;
    }
}