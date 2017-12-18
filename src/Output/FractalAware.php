<?php

declare(strict_types=1);

namespace Choredo\Output;

use League\Fractal\Manager;

interface FractalAware
{
    /**
     * @param Manager $manager
     *
     * @return mixed
     */
    public function setManager(Manager $manager);
}
