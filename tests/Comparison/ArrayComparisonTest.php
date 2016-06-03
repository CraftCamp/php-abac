<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\ArrayComparison;

use PhpAbac\Manager\ComparisonManager;
use PhpAbac\Manager\AttributeManager;

use PhpAbac\Model\PolicyRuleAttribute;
use PhpAbac\Model\Attribute;

use PhpAbac\Example\User;
use PhpAbac\Example\Visa;

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
    
    public function testContains() {
        $countries = include(__DIR__ . '/../fixtures/countries.php');
        $visas = include(__DIR__ . '/../fixtures/visas.php');
        $policyRuleAttributes = [
            (new PolicyRuleAttribute())
            ->setAttribute(
                (new Attribute())
                ->setType('resource')
                ->setProperty('country.code')
                ->setName('Code Pays')
                ->setSlug('code-pays')
            )
            ->setComparison('isEqual')
            ->setComparisonType('string')
            ->setValue('US'),
            (new PolicyRuleAttribute())
            ->setAttribute(
                (new Attribute())
                ->setType('resource')
                ->setProperty('lastRenewal')
                ->setName('Dernier renouvellement')
                ->setSlug('dernier-renouvellement')
            )
            ->setComparison('isMoreRecentThan')
            ->setComparisonType('datetime')
            ->setValue('-1Y'),
        ];
        $extraData = [
            'attribute' => 
                (new Attribute())
                ->setProperty('visas')
                ->setName('Visas')
                ->setSlug('visas')
                ->setType('resource')
                ->setValue([$visas[0], $visas[1]])
            ,
            'user' => 
                (new User())
                ->setId(1)
                ->setName('John Doe')
                ->setAge(36)
                ->setParentNationality('FR')
                ->addVisa($visas[0])
                ->addVisa($visas[1])
                ->setHasDoneJapd(true)
                ->setHasDrivingLicense(false)
            ,
            'resource' => null
        ];
        $this->assertFalse($this->comparison->contains($policyRuleAttributes, [$visas[0], $visas[1]], $extraData));
        $extraData['user']->addVisa($visas[2]);
        $extraData['attribute']->setValue($visas);
        $this->assertTrue($this->comparison->contains($policyRuleAttributes, [$visas[0], $visas[1], $visas[2]], $extraData));
    }
}
