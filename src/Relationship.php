<?php


namespace Choredo;


class Relationship
{
    private $data;
    private $name;

    /**
     * Relationship constructor.
     * @param string $name
     * @param $data
     */
    public function __construct(string $name, $data = null)
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null
     */
    public function getData()
    {
        return $this->data;
    }
}