<?php
namespace PhpAbac\Test;

use PhpAbac\AbacFactory;

class AbacTest extends \PHPUnit\Framework\TestCase
{
    /** @var array * */
    protected $basicSet;
    /** @var array * */
    protected $multipleRulesetSet;
    /** @var array **/
    protected $getterParamsSet;
    /** @var array **/
    protected $importSet;

    public function setUp(): void
    {
        $this->basicSet = [
            AbacFactory::getAbac([__DIR__ . '/fixtures/policy_rules.yml']),
            AbacFactory::getAbac([__DIR__ . '/fixtures/policy_rules.json']),
        ];
        $this->multipleRulesetSet = [
            AbacFactory::getAbac([__DIR__ . '/fixtures/policy_rules_with_array.yml']),
            AbacFactory::getAbac([__DIR__ . '/fixtures/policy_rules_with_array.json']),
            AbacFactory::getAbac(['policy_rules_with_array.yml'], __DIR__.'/fixtures/'),
            AbacFactory::getAbac(['policy_rules_with_array.json'], __DIR__.'/fixtures/'),
        ];
        $this->getterParamsSet = [
            AbacFactory::getAbac(['policy_rules_with_getter_params.yml'], __DIR__.'/fixtures/'),
        ];
        $this->importSet = [
            AbacFactory::getAbac(['policy_rules_with_import.yml'], __DIR__.'/fixtures/'),
        ];
    }

    public function testEnforce()
    {
        $countries = include('tests/fixtures/countries.php');
        $visas = include('tests/fixtures/visas.php');
        $users = include('tests/fixtures/users.php');
        $vehicles = include('tests/fixtures/vehicles.php');

        foreach ($this->basicSet as $abac) {
            $this->assertTrue($abac->enforce('nationality-access', $users[3]));
            $this->assertFalse($abac->enforce('nationality-access', $users[1]));
            $this->assertEquals(['japd'], $abac->getErrors());

            // getenv() don't work in CLI scripts without putenv()
            putenv('SERVICE_STATE=OPEN');

            $this->assertTrue($abac->enforce('vehicle-homologation', $users[0], $vehicles[0]));
            $this->assertFalse($abac->enforce('vehicle-homologation', $users[2], $vehicles[1]));
            $this->assertEquals(['derniere-revision-technique'], $abac->getErrors());
            $this->assertFalse($abac->enforce('vehicle-homologation', $users[3], $vehicles[3]));
            $this->assertEquals(['permis-de-conduire'], $abac->getErrors());
            $this->assertFalse($abac->enforce('travel-to-foreign-country', $users[0], null, [
                'dynamic_attributes' => ['code-pays' => 'US']
            ]));
            $this->assertEquals(['visas'], $abac->getErrors());
            $this->assertTrue($abac->enforce('travel-to-foreign-country', $users[1], null, [
                'dynamic_attributes' => ['code-pays' => 'US']
            ]));
        }
    }


    public function testEnforceWithMultipleRulesets()
    {
        $countries = include('tests/fixtures/countries.php');
        $visas = include('tests/fixtures/visas.php');
        $users = include('tests/fixtures/users.php');

        foreach ($this->multipleRulesetSet as $abac) {
            // for this test, the attribute in error are the tested attributes of the last rule of the ruleset
            $this->assertFalse($abac->enforce('gunlaw', $users[2]));
            $this->assertEquals(['age','code-iso-du-pays'], $abac->getErrors());

            $this->assertTrue($abac->enforce('gunlaw', $users[4]));
            $this->assertTrue($abac->enforce('gunlaw', $users[0]));
            $this->assertTrue($abac->enforce('gunlaw', $users[1]));
        }
    }
    
    
    public function testEnforceWithGetterParams()
    {
        $countries = include('tests/fixtures/countries.php');
        $visas = include('tests/fixtures/visas.php');
        $users = include('tests/fixtures/users.php');
    
        foreach ($this->getterParamsSet as $abac) {
            $this->assertFalse($abac->enforce('travel-to-foreign-country', $users[0], $countries[2]));
            $this->assertEquals(['visa-specific'], $abac->getErrors());
            $this->assertTrue($abac->enforce('travel-to-foreign-country', $users[1], $countries[2]));
        }
    }

    
    public function testEnforceWithImport()
    {
        $countries = include('tests/fixtures/countries.php');
        $visas = include('tests/fixtures/visas.php');
        $users = include('tests/fixtures/users.php');
        foreach ($this->importSet as $abac) {
            $this->assertFalse($abac->enforce('travel-to-foreign-country', $users[0], $countries[2]));
            $this->assertEquals(['visa-specific'], $abac->getErrors());
            $this->assertTrue($abac->enforce('travel-to-foreign-country', $users[1], $countries[2]));
        }
    }
}
