<?php

namespace PhpAbac\Model;

class PolicyRuleAttribute
{
    /** @var AbstractAttribute **/
    protected $attribute;
    /** @var string **/
    protected $comparisonType;
    /** @var string **/
    protected $comparison;
    /** @var mixed **/
    protected $value;
    /** @var array **/
    protected $extraData = [];
    /** @var array Extended parameter */
    protected $getter_params_a = [];

    public function setAttribute(AbstractAttribute $attribute): PolicyRuleAttribute
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getAttribute(): AbstractAttribute
    {
        return $this->attribute;
    }

    public function setComparisonType(string $comparisonType): PolicyRuleAttribute
    {
        $this->comparisonType = $comparisonType;

        return $this;
    }

    public function getComparisonType(): string
    {
        return $this->comparisonType;
    }

    public function setComparison(string $comparison): PolicyRuleAttribute
    {
        $this->comparison = $comparison;

        return $this;
    }

    public function getComparison(): string
    {
        return $this->comparison;
    }

    public function setValue($value): PolicyRuleAttribute
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }
    
    public function setExtraData(array $extraData): PolicyRuleAttribute
    {
        $this->extraData = $extraData;
        
        return $this;
    }
    
    public function addExtraData(string $key, $value): PolicyRuleAttribute
    {
        $this->extraData[$key] = $value;
        
        return $this;
    }
    
    public function removeExtraData(string $key): PolicyRuleAttribute
    {
        if (isset($this->extraData[$key])) {
            unset($this->extraData[$key]);
        }
        return $this;
    }
    
    public function getExtraData(): array
    {
        return $this->extraData;
    }
    
    public function setGetterParams(array $value): PolicyRuleAttribute
    {
        $this->getter_params_a = $value;
        
        return $this;
    }
    
    public function getGetterParams(): array
    {
        return $this->getter_params_a;
    }
}
