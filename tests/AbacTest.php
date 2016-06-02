<?php

namespace PhpAbac\Test;

use PhpAbac\Abac;

class AbacTest extends \PHPUnit_Framework_TestCase
{
    /** @var Abac **/
    protected $abac;

    public function setUp()
    {
        $this->abac = new Abac([__DIR__ . '/fixtures/policy_rules.yml']);
    }

    public function testEnforce()
    {
        $visas = include('tests/fixtures/visas.php');
        $users = include('tests/fixtures/users.php');
        $vehicles = include('tests/fixtures/vehicles.php');
        $this->assertTrue($this->abac->enforce('nationality-access', $users[3]));
        $this->assertEquals([
            'japd',
        ], $this->abac->enforce('nationality-access', $users[1]));

        // getenv() don't work in CLI scripts without putenv()
        putenv('SERVICE_STATE=OPEN');
        
        $this->assertTrue($this->abac->enforce('vehicle-homologation', $users[0], $vehicles[0], [
            'dynamic_attributes' => ['proprietaire' => 1]
        ]));
        $this->assertEquals([
            'derniere-revision-technique'
        ],$this->abac->enforce('vehicle-homologation', $users[2], $vehicles[1], [
            'dynamic_attributes' => ['proprietaire' => 3]
        ]));
        $this->assertEquals([
            'permis-de-conduire'
        ], $this->abac->enforce('vehicle-homologation', $users[3], $vehicles[3], [
            'dynamic_attributes' => ['proprietaire' => 4]
        ]));
    }
}
