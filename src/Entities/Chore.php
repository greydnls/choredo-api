<?php

declare(strict_types=1);

namespace Choredo\Entities;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="chores")
 * @ORM\HasLifeCycleCallbacks
 */
class Chore
{
    use Behaviors\HasUuid;
    use Behaviors\HasCreatedDate;
    use Behaviors\HasUpdatedDate;
    use Behaviors\BelongsToFamily;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     nullable=true
     * )
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(
     *     type="integer",
     *     nullable=true
     * )
     */
    private $value;

    public function __construct(
        Family $family,
        UuidInterface $id,
        string $name,
        string $description = null,
        int $value = null
    ) {
        $this->id          = $id;
        $this->name        = $name;
        $this->description = $description;
        $this->value       = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}
