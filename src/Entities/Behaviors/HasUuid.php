<?php

namespace Choredo\Entities\Behaviors;

use Ramsey\Uuid\UuidInterface;

trait HasUuid
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }
}