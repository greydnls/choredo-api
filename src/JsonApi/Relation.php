<?php


namespace Choredo\JsonApi;


class Relation
{
    /**
     * @var string
     */
    private $type;
    private $id;

    /**
     * Relationship constructor.
     * @param string $type
     * @param $id
     */
    public function __construct(string $type, $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}