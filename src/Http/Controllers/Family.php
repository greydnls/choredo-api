<?php

namespace Choredo\Http\Controllers;

use Choredo\Entities;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Family extends Controller
{
    /**
     * @var Manager
     */
    private $viewManager;

    /**
     * Family constructor.
     * @param Manager $viewManager
     */
    public function __construct(Manager $viewManager)
    {
        $this->viewManager = $viewManager;
    }

    public function show(string $uuidString)
    {
        $id = Uuid::fromString($uuidString);
        $family = new Entities\Family($id, 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHILD, 0);

        return $this->viewManager->createData(
            new Item($family, new Entities\Transformers\Family(), 'family')
        )->toJson();
    }
}
