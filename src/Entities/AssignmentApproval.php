<?php

declare(strict_types=1);

namespace Choredo\Entities;

use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="Choredo\Repositories\AssignmentApprovalRepository")
 * @ORM\Table(name="assignment_approvals")
 */
class AssignmentApproval
{
    use Behaviors\HasUuid;
    use Behaviors\HasCreatedDate;
    use Behaviors\HasUpdatedDate;
    use Behaviors\BelongsToFamily;

    public const API_ENTITY_TYPE = 'assignment_approvals';

    /**
     * @var AssignmentCompletion
     *
     * @ORM\OneToOne(targetEntity="Choredo\Entities\AssignmentCompletion", inversedBy="approval")
     * @ORM\JoinColumn(name="completion_id", referencedColumnName="id", nullable=false)
     */
    private $completion;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="Choredo\Entities\Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
     */
    private $account;

    /**
     * AssignmentApproval constructor.
     *
     * @param \Ramsey\Uuid\UuidInterface $id
     * @param \Choredo\Entities\Family   $family
     * @param AssignmentCompletion       $completion
     * @param Account                    $account
     */
    public function __construct(
        UuidInterface $id,
        Family $family,
        AssignmentCompletion $completion,
        Account $account
    ) {
        static::validate($id, $family, $completion, $account);
        $this->id         = $id;
        $this->family     = $family;
        $this->completion = $completion;
        $this->account    = $account;
    }

    /**
     * @return AssignmentCompletion
     */
    public function getCompletion(): AssignmentCompletion
    {
        return $this->completion;
    }

    /**
     * @param AssignmentCompletion $completion
     *
     * @return AssignmentApproval
     */
    public function setCompletion(AssignmentCompletion $completion): self
    {
        $this->completion = $completion;

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return AssignmentApproval
     */
    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    private static function validate($id, $family, $completion, $account)
    {
        return Assert::lazy()
                     ->that($id, 'AssignmentApproval::id')->isInstanceOf(UuidInterface::class)
                     ->that($family, 'AssignmentApproval::family')->isInstanceOf(Family::class)
                     ->that($completion, 'AssignmentApproval::assignmentCompletion')->isInstanceOf(
                AssignmentCompletion::class
            )
                     ->that($account, 'AssignmentApproval::account')->isInstanceOf(Account::class)
                     ->verifyNow()
            ;
    }
}
