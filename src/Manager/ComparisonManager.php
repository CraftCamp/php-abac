<?php

namespace PhpAbac\Manager;

use PhpAbac\Model\PolicyRuleAttribute;

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
    /** @var array **/
    protected $rejectedAttributes = [];
    
    /**
     * @param \PhpAbac\Manager\AttributeManager $manager
     */
    public function __construct(AttributeManager $manager) {
        $this->attributeManager = $manager;
    }
    
    /**
     * This method retrieve the comparison class, instanciate it,
     * and then perform the configured comparison
     * It does return a control value for special operations,
     * but the real check is at the end of the enforce() method,
     * when the rejected attributes are counted
     * 
     * @param PolicyRuleAttribute $pra
     * @return bool
     */
    public function compare(PolicyRuleAttribute $pra) {
        $attribute = $pra->getAttribute();
        // The expected value can be set in the configuration as dynamic
        // In this case, we retrieve the expected value in the passed options
        $praValue =
            ($pra->getValue() === 'dynamic')
            ? $this->getDynamicAttribute($attribute->getSlug())
            : $pra->getValue()
        ;
        // Checking that the configured comparison type is available
        if(!isset($this->comparisons[$pra->getComparisonType()])) {
            throw new \InvalidArgumentException('The requested comparison class does not exist');
        }
        // The comparison class will perform the attribute check with the configured method
        // For more complex comparisons, the comparison manager is injected
        $comparison = new $this->comparisons[$pra->getComparisonType()]($this);
        if(!method_exists($comparison, $pra->getComparison())) {
            throw new \InvalidArgumentException('The requested comparison method does not exist');
        }
        // Then the comparison is performed with needed
        $result = $comparison->{$pra->getComparison()}($praValue, $attribute->getValue(), $pra->getExtraData());
        // If the checked attribute is not valid, the attribute slug is marked as rejected
        // The rejected attributes will be returned instead of the expected true boolean
        if($result !== true) {
            if(!in_array($attribute->getSlug(), $this->rejectedAttributes)) {
                $this->rejectedAttributes[] = $attribute->getSlug();
            }
            return false;
        }
        return true;
    }
    
    /**
     * @param array $dynamicAttributes
     */
    public function setDynamicAttributes($dynamicAttributes) {
        $this->dynamicAttributes = $dynamicAttributes;
    }
    
    /**
     * A dynamic attribute is a value given by the user code as an option
     * If a policy rule attribute is dynamic,
     * we check that the developer has given a dynamic value in the options
     * 
     * Dynamic attributes are given with slugs as key
     * 
     * @param string $attributeSlug
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getDynamicAttribute($attributeSlug) {
        if(!isset($this->dynamicAttributes[$attributeSlug])) {
            throw new \InvalidArgumentException("The dynamic value for attribute $attributeSlug was not given");
        }
        return $this->dynamicAttributes[$attributeSlug];
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
    
    /**
     * This method is called when all the policy rule attributes are checked
     * All along the comparisons, the failing attributes slugs are stored
     * If the rejected attributes array is not empty, it means that the rule is not enforced
     * 
     * @return array|bool
     */
    public function getResult() {
        $result =
            (count($this->rejectedAttributes) > 0)
            ? $this->rejectedAttributes
            : true
        ;
        $this->rejectedAttributes = [];
        return $result;
    }
}