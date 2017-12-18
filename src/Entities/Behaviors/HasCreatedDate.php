<?php

declare(strict_types=1);

namespace Choredo\Entities\Behaviors;

trait HasCreatedDate
{
    /**
     * @ORM\Column(
     *     type="datetime",
     *     name="created"
     * )
     *
     * @var \DateTime
     */
    private $createdDate;

    /** @ORM\PrePersist */
    public function updateCreatedDate()
    {
        if (null === $this->createdDate) {
            $this->createdDate = new \DateTime();
        }
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }
}
