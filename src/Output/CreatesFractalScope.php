<?php


namespace Choredo\Output;

use Assert\Assertion;
use Doctrine\ORM\Tools\Pagination\Paginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\DoctrinePaginatorAdapter;
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
     * @param array|Paginator $data
     * @param TransformerAbstract $transformer
     * @param string $type
     * @param string $path
     * @return Scope
     */
    public function outputCollection(
        $data,
        TransformerAbstract $transformer,
        string $type,
        string $path = ""
    ): Scope {
        Assertion::true(((is_array($data)) || $data instanceof Paginator));

        if ($data instanceof Paginator) {
            $collection = new Collection(iterator_to_array($data->getIterator()), $transformer, $type);
            $perPage = $data->getQuery()->getMaxResults();
            $collection->setPaginator(
                new DoctrinePaginatorAdapter(
                    $data,
                    function ($page) use ($path, $perPage) {
                        return \Choredo\getBaseUrl() . $path .
                               "?page[limit]={$perPage}&page[offset]=" . $perPage * ($page-1);
                    }
                )
            );

            return $this->manager->createData($collection);
        }

        if (is_array($data)) {
            $collection = new Collection($data, $transformer, $type);

            return $this->manager->createData($collection);
        }
    }
}
