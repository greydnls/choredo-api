<?php

declare(strict_types=1);

namespace Choredo\Entities;

use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use const Choredo\DAYS_OF_WEEK;
use const Choredo\SHORT_DATA_FIELD_MAX_SIZE;

/**
 * @ORM\Entity(repositoryClass="Choredo\Repositories\ChildRepository")
 * @ORM\Table(name="chores")
 * @ORM\HasLifeCycleCallbacks
 */
class Chore
{
    use Behaviors\HasUuid;
    use Behaviors\HasCreatedDate;
    use Behaviors\HasUpdatedDate;
    use Behaviors\BelongsToFamily;

    public const API_ENTITY_TYPE = 'chores';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $schedule;

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
        UuidInterface $id,
        Family $family,
        string $name,
        array $schedule,
        string $description = null,
        int $value = null
    ) {
        static::validate($id, $family, $name, $schedule, $description, $value);
        $this->id          = $id;
        $this->family      = $family;
        $this->name        = $name;
        $this->schedule    = $schedule;
        $this->description = $description;
        $this->value       = $value;
    }

    /**
     * @param  $id
     * @param  $family
     * @param  $name
     * @param  $schedule
     * @param  $description
     * @param  $value
     *
     * @return bool
     */
    public static function validate($id, $family, $name, $schedule, $description, $value): bool
    {
        return Assert::lazy()
                     ->that($id, 'Chore::id')->isInstanceOf(UuidInterface::class)
                     ->that($family, 'Chore::family')->isInstanceOf(Family::class)
                     ->that($name, 'Chore::name')->alnum()->maxLength(SHORT_DATA_FIELD_MAX_SIZE)
                     ->that($schedule, 'Chore::schedule')->isArray()->all()->boolean()
                     ->that(array_keys($schedule), 'Chore::schedule')->all()->choice(DAYS_OF_WEEK)
                     ->that($description, 'Chore::description')->nullOr()->maxLength(SHORT_DATA_FIELD_MAX_SIZE)
                     ->that($value, 'Chore::value')->nullOr()->integer()
                     ->verifyNow()
            ;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Chore
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getSchedule(): array
    {
        return $this->schedule;
    }

    /**
     * @param array $schedule
     *
     * @return Chore
     */
    public function setSchedule(array $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Chore
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     *
     * @return Chore
     */
    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
