<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\StringComparison;

use PhpAbac\Manager\ComparisonManager;
use PhpAbac\Manager\AttributeManager;

class StringComparisonTest extends \PHPUnit_Framework_TestCase
{
    /** @var StringComparison **/
    protected $comparison;

    public function setUp()
    {
        $this->comparison = new StringComparison(new ComparisonManager(new AttributeManager([])));
    }

    public function testIsEqual()
    {
        $this->assertTrue($this->comparison->isEqual('john-doe', 'john-doe'));
        $this->assertFalse($this->comparison->isEqual('john-doe', 'john-DOE'));
    }

    public function testIsNotEqual()
    {
        $this->assertTrue($this->comparison->isNotEqual('john-doe', 'john-DOE'));
        $this->assertFalse($this->comparison->isNotEqual('john-doe', 'john-doe'));
    }
}
