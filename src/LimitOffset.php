<?php

declare(strict_types=1);

namespace Choredo;

class LimitOffset
{
    /**
     * @var int
     */
    private $offset;
    /**
     * @var int
     */
    private $limit;

    /**
     * Pagination constructor.
     *
     * @param int $limit
     * @param int $offset
     */
    public function __construct(int $limit, int $offset)
    {
        $this->offset = $offset;
        $this->limit  = $limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}
