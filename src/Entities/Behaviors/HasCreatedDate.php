<?php

namespace Choredo\Entities\Behaviors;

trait HasCreatedDate
{
    /**
     * @ORM\Column(
     *     type="datetime",
     *     name="created",
     *     nullable=false
     * )
     *
     * @var \DateTime
     */
    private $createdDate;

    /** @ORM\PrePersist */
    public function updateCreatedDate()
    {
        if ($this->createdDate === null) {
            $this->createdDate = new \DateTime();
        }
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate() :\DateTime
    {
        return $this->createdDate;
    }
}