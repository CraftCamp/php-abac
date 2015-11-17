<?php

namespace PhpAbac\Test;

use PhpAbac\Abac;

class AbacTestCase extends \PHPUnit_Framework_TestCase
{
    public function loadFixture($fixture)
    {
        if (!is_file(__DIR__."/fixtures/$fixture.sql")) {
            throw new \InvalidArgumentException("The asked fixture file $fixture.sql does not exists");
        }
        Abac::get('pdo-connection')->exec(file_get_contents(__DIR__."/fixtures/$fixture.sql"));
    }
}
