<?php

namespace PhpAbac\Manager;

use PhpAbac\Comparison\ArrayComparison;
use PhpAbac\Comparison\BooleanComparison;
use PhpAbac\Comparison\DatetimeComparison;
use PhpAbac\Comparison\NumericComparison;
use PhpAbac\Comparison\StringComparison;

class ComparisonManager {
    /** @var \PhpAbac\Manager\AttributeManager **/
    protected $attributeManager;
    /** @var array **/
    protected $comparisons = [
        'array' => ArrayComparison::class,
        'boolean' => BooleanComparison::class,
        'datetime' => DatetimeComparison::class,
        'numeric' => NumericComparison::class,
        'string' => StringComparison::class,
    ];
    
    /**
     * @param \PhpAbac\Manager\AttributeManager $manager
     */
    public function __construct(AttributeManager $manager) {
        $this->attributeManager = $manager;
    }
    
    /**
     * @param string $type
     * @param string $method
     * @param mixed $expectedValue
     * @param mixed $value
     * @param array $extraData
     * @return bool
     */
    public function compare($type, $method, $expectedValue, $value, $extraData = []) {
        if(!isset($this->comparisons[$type])) {
            throw new \InvalidArgumentException('The requested comparison class does not exist');
        }
        $comparison = new $this->comparisons[$type]($this);
        if(!method_exists($comparison, $method)) {
            throw new \InvalidArgumentException('The requested comparison method does not exist');
        }
        return $comparison->{$method}($expectedValue, $value, $extraData);
    }
    
    /**
     * @param string $type
     * @param string $class
     */
    public function addComparison($type, $class) {
        $this->comparisons[$type] = $class;
    }
    
    /**
     * @return \PhpAbac\Manager\AttributeManager
     */
    public function getAttributeManager() {
        return $this->attributeManager;
    }
}