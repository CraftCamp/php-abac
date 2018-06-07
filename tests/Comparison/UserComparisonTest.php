<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\UserComparison;

use Symfony\Component\Config\FileLocator;

use PhpAbac\Manager\{
    AttributeManager,
    ComparisonManager,
    ConfigurationManager
};
use PhpAbac\Example\User;

class UserComparisonTest extends \PHPUnit\Framework\TestCase
{
    /** @var ArrayComparison **/
    protected $comparison;

    public function setUp()
    {
        $configuration = new ConfigurationManager(new FileLocator());
        $configuration->parseConfigurationFile([__DIR__.'/../fixtures/policy_rules.yml']);

        $this->comparison = new UserComparison(new ComparisonManager(new AttributeManager($configuration->getAttributes())));
    }

    public function testIsFieldEqual()
    {
        $countries = include(__DIR__ . '/../fixtures/countries.php');
        $visas = include(__DIR__ . '/../fixtures/visas.php');
        $extraData = [
            'user' =>
                (new User())
                ->setId(1)
                ->setParentNationality('UK')
        ];
        $this->assertFalse($this->comparison->isFieldEqual('main_user.parentNationality', 'FR', $extraData));
        $this->assertTrue($this->comparison->isFieldEqual('main_user.parentNationality', 'UK', $extraData));
    }
}
