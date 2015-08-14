<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\StringComparison;

class StringComparisonTest extends \PHPUnit_Framework_TestCase {
    /** @var StringComparison **/
    protected $comparison;
    
    public function setUp() {
        $this->comparison = new StringComparison();
    }
    
    public function testIsEqual() {
        $this->assertTrue($this->comparison->isEqual('john-doe', 'john-doe'));
        $this->assertFalse($this->comparison->isEqual('john-doe', 'john-DOE'));
    }
}