<?php

namespace Choredo\Entities;

use Assert\Assertion;
use Choredo\Entities\Behaviors;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Family
 * @package Choredo\Entities
 *
 * @ORM\Entity
 * @ORM\Table(name="families")
 * @ORM\HasLifeCycleCallbacks
 */
class Family
{
    use Behaviors\HasUuid;
    use Behaviors\HasCreatedDate;
    use Behaviors\HasUpdatedDate;

    const PAYMENT_STRATEGY_PER_CHORE = 'per_chore';
    const PAYMENT_STRATEGY_PER_CHILD = 'per_child';
    const MIN_COMPLETION_THRESHOLD = 0;
    const MAX_COMPLETION_THRESHOLD = 100;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="payment_strategy")
     */
    private $paymentStrategy;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, name="completion_threshold")
     */
    private $completionThreshold;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", name="week_start_day")
     */
    private $weekStartDay;

    /**
     * Family constructor.
     * @param UuidInterface $id
     * @param string $name
     * @param string $paymentStrategy
     * @param int $weekStartDay
     * @param int $completionThreshold
     */
    public function __construct(
        UuidInterface $id,
        string $name,
        string $paymentStrategy,
        int $weekStartDay,
        int $completionThreshold = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->weekStartDay = $weekStartDay;
        $this->setPaymentStrategy($paymentStrategy, $completionThreshold);
    }

    /**
     * Set paymentStrategy with validation rules
     *
     * @param string $paymentStrategy
     * @param int|null $completionThreshold
     */
    private function setPaymentStrategy(string $paymentStrategy, int $completionThreshold = null)
    {
        Assertion::choice(
            $paymentStrategy,
            [static::PAYMENT_STRATEGY_PER_CHILD, static::PAYMENT_STRATEGY_PER_CHORE],
            "Invalid payment strategy '$paymentStrategy' specified."
        );

        if ($paymentStrategy === static::PAYMENT_STRATEGY_PER_CHILD) {
            Assertion::between(
                $completionThreshold,
                0,
                100,
                "When per-child payment strategy is specified, "
                . " a completion threshold value between 0 and 100 must also be set"
            );
        }

        $this->paymentStrategy = $paymentStrategy;
        $this->completionThreshold = $completionThreshold;
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
    public function getPaymentStrategy(): string
    {
        return $this->paymentStrategy;
    }

    /**
     * @return int|null
     */
    public function getCompletionThreshold(): ?int
    {
        return $this->completionThreshold;
    }

    /**
     * @return int
     */
    public function getWeekStartDay(): int
    {
        return $this->weekStartDay;
    }
}
