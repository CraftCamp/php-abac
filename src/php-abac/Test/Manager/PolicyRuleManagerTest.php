<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Abac;
use PhpAbac\Test\AbacTestCase;

class PolicyRuleManagerTest extends AbacTestCase {
    /** @var \PhpAbac\Manager\PolicyRuleManager **/
    private $manager;
    
    public function setUp() {
        new Abac(new \PDO(
            'mysql:host=' . $GLOBALS['MYSQL_DB_HOST'] . ';' .
            'dbname=' . $GLOBALS['MYSQL_DB_DBNAME'],
            $GLOBALS['MYSQL_DB_USER'],
            $GLOBALS['MYSQL_DB_PASSWD'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]
        ));
        
        $this->loadFixture('policy_rules');
        
        $this->manager = Abac::get('policy-rule-manager');
    }
    
    public function testCreate() {
        $policyRule = $this->manager->create('citizenship', [
            [
                'comparison' => 'greaterThan',
                'value' => 18,
                'attribute' => [
                    'name' => 'age',
                    'table' => 'user',
                    'column' => 'age',
                    'criteria_column' => 'id'
                ]
            ],
            [
                'comparison' => 'equal',
                'value' => 'FR',
                'attribute' => [
                    'name' => 'nationalité',
                    'table' => 'user',
                    'column' => 'nationality',
                    'criteria_column' => 'id'
                ]
            ],
            [
                'comparison' => 'equal',
                'value' => true,
                'attribute' => [
                    'name' => 'JAPD',
                    'table' => 'user',
                    'column' => 'has_done_japd',
                    'criteria_column' => 'id'
                ]
            ]
        ]);
        // Test the policy rule
        $this->assertEquals('citizenship', $policyRule->getName());
        $this->assertInstanceof('DateTime', $policyRule->getCreatedAt());
        $this->assertInstanceof('DateTime', $policyRule->getUpdatedAt());
        
        // Test the cascade created PolicyRuleAttribute entities
        $policyRuleAttributes = $policyRule->getPolicyRuleAttributes();
        
        $this->assertCount(3, $policyRuleAttributes);
        $this->assertInstanceof('PhpAbac\Model\PolicyRuleAttribute', $policyRuleAttributes[0]);
        $this->assertEquals('greaterThan', $policyRuleAttributes[0]->getComparison());
        $this->assertEquals(18, $policyRuleAttributes[0]->getValue());
        
        // Test the created Attribute entity
        $attribute = $policyRuleAttributes[0]->getAttribute();
        
        $this->assertInstanceof('PhpAbac\Model\Attribute', $attribute);
        $this->assertEquals('age', $attribute->getName());
        $this->assertEquals('user', $attribute->getTable());
        $this->assertEquals('age', $attribute->getColumn());
        $this->assertEquals('id', $attribute->getCriteriaColumn());
    }
    
    public function testGetRuleByName() {
        $policyRule = $this->manager->getRuleByName('test-rule');
        
        $this->assertInstanceof('PhpAbac\Model\PolicyRule', $policyRule);
        $this->assertEquals(2, $policyRule->getId(2));
        $this->assertEquals('test-rule', $policyRule->getName());
        $this->assertInstanceOf('DateTime', $policyRule->getCreatedAt());
        $this->assertInstanceOf('DateTime', $policyRule->getUpdatedAt());
    }
}