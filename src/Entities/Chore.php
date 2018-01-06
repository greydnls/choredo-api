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
        static::validate($name, $schedule, $description);
        $this->id          = $id;
        $this->family      = $family;
        $this->name        = $name;
        $this->schedule    = $schedule;
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
     * @return array
     */
    public function getSchedule(): array
    {
        return $this->schedule;
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
     * @param string      $name
     * @param array       $schedule
     * @param string|null $description
     *
     * @return bool
     */
    public static function validate(string $name, array $schedule, string $description = null): bool
    {
        return Assert::lazy()
                     ->that($name, 'Chore::name')->maxLength(SHORT_DATA_FIELD_MAX_SIZE)
                     ->that(array_keys($schedule), 'Chore::schedule')
                     ->all()->choice(DAYS_OF_WEEK, 'Schedule keys must be valid weekdays')
                     ->that($schedule, 'Chore::schedule')
                     ->all()->boolean('Schedule values must be true or false')
                     ->that($description, 'Chore::description')
                     ->nullOr()->maxLength(SHORT_DATA_FIELD_MAX_SIZE)
                     ->verifyNow()
            ;
    }
}
