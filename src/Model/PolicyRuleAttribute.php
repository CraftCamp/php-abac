<?php

namespace PhpAbac\Model;

/**
 * Class PolicyRuleAttribute
 *
 * @package PhpAbac\Model
 */
class PolicyRuleAttribute
{
    /**
     * @var AbstractAttribute
     */
    protected $attribute;
    /**
     * @var string
     */
    protected $comparisonType;
    /**
     * @var string
     */
    protected $comparison;
    /**
     * @var mixed
     */
    protected $value;
    /**
     * @var array
     */
    protected $extraData = [];
    /**
     * @var array Extended parameter
     */
    protected $getter_params_a = [];

    /**
     * @param AbstractAttribute $attribute
     *
     * @return PolicyRuleAttribute
     */
    public function setAttribute(AbstractAttribute $attribute): PolicyRuleAttribute
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return AbstractAttribute
     */
    public function getAttribute(): AbstractAttribute
    {
        return $this->attribute;
    }

    /**
     * @param string $comparisonType
     *
     * @return PolicyRuleAttribute
     */
    public function setComparisonType(string $comparisonType): PolicyRuleAttribute
    {
        $this->comparisonType = $comparisonType;

        return $this;
    }

    /**
     * @return string
     */
    public function getComparisonType(): string
    {
        return $this->comparisonType;
    }

    /**
     * @param string $comparison
     *
     * @return PolicyRuleAttribute
     */
    public function setComparison(string $comparison): PolicyRuleAttribute
    {
        $this->comparison = $comparison;

        return $this;
    }

    /**
     * @return string
     */
    public function getComparison(): string
    {
        return $this->comparison;
    }

    /**
     * @param $value
     *
     * @return PolicyRuleAttribute
     */
    public function setValue($value): PolicyRuleAttribute
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param array $extraData
     *
     * @return PolicyRuleAttribute
     */
    public function setExtraData(array $extraData): PolicyRuleAttribute
    {
        $this->extraData = $extraData;
        
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     *
     * @return PolicyRuleAttribute
     */
    public function addExtraData(string $key, $value): PolicyRuleAttribute
    {
        $this->extraData[$key] = $value;
        
        return $this;
    }

    /**
     * @param string $key
     *
     * @return PolicyRuleAttribute
     */
    public function removeExtraData(string $key): PolicyRuleAttribute
    {
        if (isset($this->extraData[$key])) {
            unset($this->extraData[$key]);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getExtraData(): array
    {
        return $this->extraData;
    }

    /**
     * @param array $value
     *
     * @return PolicyRuleAttribute
     */
    public function setGetterParams(array $value): PolicyRuleAttribute
    {
        $this->getter_params_a = $value;
        
        return $this;
    }

    /**
     * @return array
     */
    public function getGetterParams(): array
    {
        return $this->getter_params_a;
    }
}
