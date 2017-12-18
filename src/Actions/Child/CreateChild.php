<?php

declare(strict_types=1);

namespace Choredo\Actions\Child;

use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;

class CreateChild implements FractalAware
{
	use CreatesFractalScope;
}
