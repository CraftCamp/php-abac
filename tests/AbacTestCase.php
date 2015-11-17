<?php

namespace PhpAbac\Test;

use PhpAbac\Abac;

class AbacTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PDO
     */
    private $connection;

    public function loadFixture($fixture)
    {
        if (!is_file(__DIR__ . "/fixtures/$fixture.sql")) {
            throw new \InvalidArgumentException("The asked fixture file $fixture.sql does not exists");
        }
        $this->getConnection()->exec(file_get_contents(__DIR__ . "/fixtures/$fixture.sql"));
    }

    /**
     * @return \PDO
     */
    protected function getConnection()
    {
        if (empty($this->connection)) {
            $this->connection = new \PDO('sqlite::memory:', null, null, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        }

        return $this->connection;
    }
}
