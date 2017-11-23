<?php

namespace Choredo;

class Filter
{
    /**
     * @var string
     */
    private $field;
    /**
     * @var mixed
     */
    private $value;
    /**
     * @var callable|null
     */
    private $transform;

    /**
     * Filter constructor.
     * @param string $field
     * @param mixed $value
     * @param callable|null $transform
     */
    public function __construct(string $field, $value, callable $transform = null)
    {
        $this->field = $field;
        $this->value = $value;
        $this->transform = $transform;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getValue()
    {
        return is_callable($this->transform) ? call_user_func($this->transform, $this->value) : $this->value;
    }
}
