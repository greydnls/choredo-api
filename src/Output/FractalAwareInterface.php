<?php

declare(strict_types=1);

namespace Choredo\Output;

use League\Fractal\Manager;

interface FractalAwareInterface
{
    /**
     * @param Manager $manager
     *
     * @return mixed
     */
    public function setManager(Manager $manager);
}
