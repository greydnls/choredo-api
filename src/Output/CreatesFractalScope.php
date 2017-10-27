<?php


namespace Choredo\Output;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;

trait CreatesFractalScope
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @param Manager $manager
     */
    public function setManager(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @param string $type
     * @return Scope
     */
    public function outputItem(
        $data,
        TransformerAbstract $transformer,
        string $type
    ): Scope {
        return $this->manager->createData(new Item($data, $transformer, $type));
    }

    /**
     * @param array $data
     * @param TransformerAbstract $transformer
     * @param string $type
     * @return Scope
     */
    public function outputCollection(
        array $data,
        TransformerAbstract $transformer,
        string $type
    ): Scope {
        return $this->manager->createData(
            new Collection($data, $transformer, $type)
        );
    }
}