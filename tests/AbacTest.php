<?php
namespace PhpAbac\Test;

date_default_timezone_set('UTC');


use PhpAbac\Abac;

class AbacTest extends \PHPUnit\Framework\TestCase
{
    /** @var Abac[] $abac_a * */
    protected $abac_a;
    /** @var Abac[] $abac_array_a * */
    protected $abac_array_a;
    /** @var Abac[] $abac_getter_params_a * */
    protected $abac_getter_params_a;
    /** @var Abac[] $abac_import_a * */
    protected $abac_import_a;

    public function setUp()
    {
        $this->abac_a = [
            new Abac([__DIR__ . '/fixtures/policy_rules.yml']),
            new Abac([__DIR__ . '/fixtures/policy_rules.json']),
        ];
        $this->abac_array_a = [
            new Abac([__DIR__ . '/fixtures/policy_rules_with_array.yml']),
            new Abac([__DIR__ . '/fixtures/policy_rules_with_array.json']),
            new Abac(['policy_rules_with_array.yml'], [], __DIR__.'/fixtures/'),
            new Abac(['policy_rules_with_array.json'], [], __DIR__.'/fixtures/'),
        ];
        $this->abac_getter_params_a = [
            new Abac(['policy_rules_with_getter_params.yml'], [], __DIR__.'/fixtures/'),
        ];
        $this->abac_import_a = [
            new Abac(['policy_rules_with_import.yml'], [], __DIR__.'/fixtures/'),
        ];
    }

    public function testEnforce()
    {
        $countries = include('tests/fixtures/countries.php');
        $visas = include('tests/fixtures/visas.php');
        $users = include('tests/fixtures/users.php');
        $vehicles = include('tests/fixtures/vehicles.php');

        foreach ($this->abac_a as $abac) {
            $this->assertTrue($abac->enforce('nationality-access', $users[3]));
            $this->assertEquals([
                'japd',
            ], $abac->enforce('nationality-access', $users[1]));

            // getenv() don't work in CLI scripts without putenv()
            putenv('SERVICE_STATE=OPEN');

            $this->assertTrue($abac->enforce('vehicle-homologation', $users[0], $vehicles[0]));
            $this->assertEquals(
                ['derniere-revision-technique'],
                $abac->enforce('vehicle-homologation', $users[2], $vehicles[1])
            );
            $this->assertEquals(
                ['permis-de-conduire'],
                $abac->enforce('vehicle-homologation', $users[3], $vehicles[3])
            );
            $this->assertEquals(
                ['visas'],
                $abac->enforce('travel-to-foreign-country', $users[0], null, [
                    'dynamic_attributes' => ['code-pays' => 'US']
                ])
            );
            $this->assertTrue($abac->enforce('travel-to-foreign-country', $users[1], null, [
                'dynamic_attributes' => ['code-pays' => 'US']
            ]));
        }
    }


    public function testEnforceArray()
    {
        $countries = include('tests/fixtures/countries.php');
        $visas = include('tests/fixtures/visas.php');
        $users = include('tests/fixtures/users.php');

        foreach ($this->abac_array_a as $abac) {

            // for this test, the attribute in error are the attributes tester une the last rule of the ruleset
            $this->assertEquals(['age','code-iso-du-pays'], $abac->enforce('gunlaw', $users[2]));

            $this->assertTrue($abac->enforce('gunlaw', $users[4]));
            $this->assertTrue($abac->enforce('gunlaw', $users[0]));
            $this->assertTrue($abac->enforce('gunlaw', $users[1]));
        }
    }
    
    
    public function testEnforceGetterParams()
    {
        $countries = include('tests/fixtures/countries.php');
        $visas = include('tests/fixtures/visas.php');
        $users = include('tests/fixtures/users.php');
    
        foreach ($this->abac_getter_params_a as $abac) {
        
            // for this test, the attribute in error are the attributes tester une the last rule of the ruleset
            $this->assertEquals(['visa-specific'], $abac->enforce('travel-to-foreign-country', $users[0], $countries[2]));
            $this->assertTrue($abac->enforce('travel-to-foreign-country', $users[1], $countries[2]));
        }
    }

    
    public function testEnforceImport()
    {
        $countries = include('tests/fixtures/countries.php');
        $visas = include('tests/fixtures/visas.php');
        $users = include('tests/fixtures/users.php');
        foreach ($this->abac_import_a as $abac) {
            
            // for this test, the attribute in error are the attributes tester une the last rule of the ruleset
            $this->assertEquals(['visa-specific'], $abac->enforce('travel-to-foreign-country', $users[0], $countries[2]));
            $this->assertTrue($abac->enforce('travel-to-foreign-country', $users[1], $countries[2]));
        }
    }
}
