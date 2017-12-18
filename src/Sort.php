<?php

declare(strict_types=1);

namespace Choredo;

class Sort
{
    const DIRECTION_ASCENDING  = 'ASC';
    const DIRECTION_DESCENDING = 'DESC';
    /**
     * @var string
     */
    private $field;
    /**
     * @var string
     */
    private $direction;

    /**
     * Sort constructor.
     *
     * @param string $field
     * @param string $direction
     */
    public function __construct(string $field, string $direction)
    {
        $this->field     = $field;
        $this->direction = $direction;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }
}
