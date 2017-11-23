<?php

namespace Choredo\Test;

use Choredo\Filter;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testTransformIsAppliedWhenSet()
    {
        $field = 'foo';
        $value = 'bar';
        $filter = new Filter($field, $value, 'strtoupper');
        $this->assertEquals(strtoupper($value), $filter->getValue());
    }
}
