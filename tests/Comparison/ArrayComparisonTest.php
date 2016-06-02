<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\ArrayComparison;

use PhpAbac\Manager\ComparisonManager;
use PhpAbac\Manager\AttributeManager;

class ArrayComparisonTest extends \PHPUnit_Framework_TestCase
{
    /** @var ArrayComparison **/
    protected $comparison;

    public function setUp()
    {
        $this->comparison = new ArrayComparison(new ComparisonManager(new AttributeManager([])));
    }

    public function testIsIn()
    {
        $this->assertTrue($this->comparison->isIn([
            'value',
            'expected_value',
            'another_value',
        ], 'expected_value'));
        $this->assertFalse($this->comparison->isIn([
            'value',
            'another_value',
        ], 'expected_value'));
    }

    public function testIsNotIn()
    {
        $this->assertTrue($this->comparison->isNotIn([
            'value',
            'another_value',
        ], 'expected_value'));
        $this->assertFalse($this->comparison->isNotIn([
            'value',
            'expected_value',
            'another_value',
        ], 'expected_value'));
    }

    public function testIntersect()
    {
        $this->assertTrue($this->comparison->intersect([
            'ROLE_USER',
            'ROLE_MODERATOR',
            'ROLE_ADMIN',
        ], [
            'ROLE_USER',
            'ROLE_POST_MANAGER',
        ]));
        $this->assertFalse($this->comparison->intersect([
            'ROLE_MODERATOR',
            'ROLE_ADMIN',
        ], [
            'ROLE_USER',
            'ROLE_POST_MANAGER',
        ]));
    }

    public function testDoNotIntersect()
    {
        $this->assertTrue($this->comparison->doNotIntersect([
            'ROLE_MODERATOR',
            'ROLE_ADMIN',
        ], [
            'ROLE_USER',
            'ROLE_POST_MANAGER',
        ]));
        $this->assertFalse($this->comparison->doNotIntersect([
            'ROLE_USER',
            'ROLE_MODERATOR',
            'ROLE_ADMIN',
        ], [
            'ROLE_USER',
            'ROLE_POST_MANAGER',
        ]));
    }
}
