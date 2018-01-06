<?php

declare(strict_types=1);

namespace Choredo\Entities;

use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use const Choredo\SHORT_DATA_FIELD_MAX_SIZE;

/**
 * @ORM\Entity(repositoryClass="Choredo\Repositories\AssignmentCompletionRepository")
 * @ORM\Table(name="assignment_completions")
 */
class AssignmentCompletion
{
    use Behaviors\HasUuid;
    use Behaviors\HasCreatedDate;
    use Behaviors\HasUpdatedDate;
    use Behaviors\BelongsToFamily;

    /**
     * @var Assignment
     *
     * @ORM\ManyToOne(targetEntity="Choredo\Entities\Assignment")
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id", nullable=false)
     */
    private $assignment;

    /**
     * @var Child
     *
     * @ORM\ManyToOne(targetEntity="Choredo\Entities\Child")
     * @ORM\JoinColumn(name="child_id", referencedColumnName="id", nullable=false)
     */
    private $child;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="chore_description")
     */
    private $choreDescription;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="chore_value")
     */
    private $choreValue;

    /**
     * AssignmentCompletion constructor.
     *
     * @param \Ramsey\Uuid\UuidInterface   $id
     * @param \Choredo\Entities\Family     $family
     * @param \Choredo\Entities\Assignment $assignment
     * @param \Choredo\Entities\Child      $child
     * @param string                       $choreDescription
     * @param int                          $choreValue
     */
    public function __construct(
        UuidInterface $id,
        Family $family,
        Assignment $assignment,
        Child $child,
        $choreDescription,
        $choreValue
    ) {
        static::validate($id, $family, $assignment, $child, $choreDescription, $choreValue);
        $this->id               = $id;
        $this->family           = $family;
        $this->assignment       = $assignment;
        $this->child            = $child;
        $this->choreDescription = $choreDescription;
        $this->choreValue       = $choreValue;
    }

    private static function validate($id, $family, $assignment, $child, $choreDescription, $choreValue)
    {
        return Assert::lazy()
                     ->that($id, 'AssignmentCompletion::id')->isInstanceOf(UuidInterface::class)
                     ->that($family, 'AssignmentCompletion::family')->isInstanceOf(Family::class)
                     ->that($assignment, 'AssignmentCompletion::assignment')->isInstanceOf(Assignment::class)
                     ->that($child, 'AssignmentCompletion::child')->isInstanceOf(Child::class)
                     ->that($choreDescription, 'AssignmentCompletion::choreDescription')
                     ->string()->maxLength(SHORT_DATA_FIELD_MAX_SIZE)
                     ->that($choreValue, 'AssignmentCompletion::choreValue')->integer()
                     ->verifyNow()
            ;
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
     * @return AssignmentCompletion
     */
    public function setChild(Child $child): self
    {
        $this->child = $child;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChoreDescription()
    {
        return $this->choreDescription;
    }

    /**
     * @param mixed $choreDescription
     *
     * @return AssignmentCompletion
     */
    public function setChoreDescription($choreDescription)
    {
        $this->choreDescription = $choreDescription;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChoreValue()
    {
        return $this->choreValue;
    }

    /**
     * @param mixed $choreValue
     *
     * @return AssignmentCompletion
     */
    public function setChoreValue($choreValue)
    {
        $this->choreValue = $choreValue;

        return $this;
    }

    /**
     * @return Assignment
     */
    public function getAssignment(): Assignment
    {
        return $this->assignment;
    }

    /**
     * @param Assignment $assignment
     *
     * @return AssignmentCompletion
     */
    public function setAssignment(Assignment $assignment): self
    {
        $this->assignment = $assignment;

        return $this;
    }
}
