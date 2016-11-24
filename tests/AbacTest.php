<?php
namespace PhpAbac\Test;

date_default_timezone_set('UTC');


use PhpAbac\Abac;

class AbacTest extends \PHPUnit_Framework_TestCase
{
    /** @var Abac[] $abac_a * */
    protected $abac_a;

    public function setUp()
    {
        $this->abac_a = [
            new Abac([__DIR__ . '/fixtures/policy_rules.yml']),
            new Abac([__DIR__ . '/fixtures/policy_rules.json'])
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


}
