<?php

declare(strict_types=1);

namespace Choredo\Entities;

use Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Assignment.
 *
 * @ORM\Entity(repositoryClass="Choredo\Repositories\AssignmentRepository")
 * @ORM\Table(name="assignments")
 * @ORM\HasLifecycleCallbacks
 */
class Assignment
{
    use Behaviors\HasUuid;
    use Behaviors\HasCreatedDate;
    use Behaviors\HasUpdatedDate;
    use Behaviors\BelongsToFamily;

    public const API_ENTITY_TYPE = 'assignments';

    /**
     * @var Child
     *
     * @ORM\ManyToOne(targetEntity="Choredo\Entities\Child", inversedBy="assignments")
     * @ORM\JoinColumn(name="child_id", referencedColumnName="id", nullable=false)
     */
    private $child;

    /**
     * @var Chore
     *
     * @ORM\ManyToOne(targetEntity="Choredo\Entities\Chore")
     * @ORM\JoinColumn(name="chore_id", referencedColumnName="id", nullable=false)
     */
    private $chore;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", name="day_of_week")
     */
    private $dayOfWeek;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Choredo\Entities\AssignmentCompletion", mappedBy="assignment")
     */
    private $completions;

    /**
     * Assignment constructor.
     *
     * @param \Ramsey\Uuid\UuidInterface $id
     * @param \Choredo\Entities\Family   $family
     * @param Child                      $child
     * @param Chore                      $chore
     * @param int                        $dayOfWeek
     */
    public function __construct(UuidInterface $id, Family $family, Child $child, Chore $chore, int $dayOfWeek)
    {
        static::validate($id, $family, $child, $chore, $dayOfWeek);
        $this->id          = $id;
        $this->family      = $family;
        $this->child       = $child;
        $this->chore       = $chore;
        $this->dayOfWeek   = $dayOfWeek;
        $this->completions = new ArrayCollection();
    }

    /**
     * @return Child
     */
    public function getChild(): Child
    {
        return $this->child;
    }

    /**
     * @param Child $child
     *
     * @return Assignment
     */
    public function setChild(Child $child): self
    {
        $this->child = $child;

        return $this;
    }

    /**
     * @return Chore
     */
    public function getChore(): Chore
    {
        return $this->chore;
    }

    /**
     * @param Chore $chore
     *
     * @return Assignment
     */
    public function setChore(Chore $chore): self
    {
        $this->chore = $chore;

        return $this;
    }

    /**
     * @return int
     */
    public function getDayOfWeek(): int
    {
        return $this->dayOfWeek;
    }

    /**
     * @param int $dayOfWeek
     *
     * @return Assignment
     */
    public function setDayOfWeek(int $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCompletions(): ArrayCollection
    {
        return $this->completions;
    }

    private static function validate($id, $family, $child, $chore, $dayOfWeek)
    {
        return Assert::lazy()
                     ->that($id, 'Assignment::id')->isInstanceOf(UuidInterface::class)
                     ->that($family, 'Assignment::family')->isInstanceOf(Family::class)
                     ->that($child, 'Assignment::child')->isInstanceOf(Child::class)
                     ->that($chore, 'Assignment::chore')->isInstanceOf(Chore::class)
                     ->that($dayOfWeek, 'Assignment::dayOfWeek')->integer()->between(0, 6)
                     ->verifyNow()
            ;
    }
}
