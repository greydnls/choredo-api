<?php


namespace Choredo\Entities;

use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;


/**
 * @package Choredo\Entities
 *
 * @ORM\Entity
 * @ORM\Table(name="chores")
 * @ORM\HasLifeCycleCallbacks
 */
class Chore
{
    use Behaviors\HasUuid;
    use Behaviors\HasCreatedDate;
    use Behaviors\HasUpdatedDate;

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
     *      type="string",
     *      nullable = true
     * )
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(
     *      type="integer",
     *      nullable=true
     * )
     */
    private $value = null;


    /**
     * @ORM\ManyToOne(targetEntity="Choredo\Entities\Family")
     * @ORM\JoinColumn(name="family_id", referencedColumnName="id", nullable=false)
     *
     * @var Family
     */
    private $family;


    public function __construct(
        Family $family,
        UuidInterface $id,
        string $name,
        string $description = null,
        int $value = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->value = $value;
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

    /**
     * @return mixed
     */
    public function getFamily()
    {
        return $this->family;
    }
}