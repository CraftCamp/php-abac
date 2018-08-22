<?php

namespace PhpAbac\Model;

class PolicyRuleAttribute
{
    /** @var \PhpAbac\Model\AbstractAttribute **/
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

    /**
     * @param \PhpAbac\Model\AbstractAttribute $attribute
     *
     * @return \PhpAbac\Model\PolicyRuleAttribute
     */
    public function setAttribute(AbstractAttribute $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return \PhpAbac\Model\AbstractAttribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param string $comparisonType
     *
     * @return \PhpAbac\Model\PolicyRuleAttribute
     */
    public function setComparisonType($comparisonType)
    {
        $this->comparisonType = $comparisonType;

        return $this;
    }

    /**
     * @return string
     */
    public function getComparisonType()
    {
        return $this->comparisonType;
    }

    /**
     * @param string $comparison
     *
     * @return \PhpAbac\Model\PolicyRuleAttribute
     */
    public function setComparison($comparison)
    {
        $this->comparison = $comparison;

        return $this;
    }

    /**
     * @return string
     */
    public function getComparison()
    {
        return $this->comparison;
    }

    /**
     * @param mixed $value
     *
     * @return \PhpAbac\Model\PolicyRuleAttribute
     */
    public function setValue($value)
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
     * @return \PhpAbac\Model\PolicyRuleAttribute
     */
    public function setExtraData($extraData)
    {
        $this->extraData = $extraData;
        
        return $this;
    }
    
    /**
     * @param string $key
     * @param string $value
     * @return \PhpAbac\Model\PolicyRuleAttribute
     */
    public function addExtraData($key, $value)
    {
        $this->extraData[$key] = $value;
        
        return $this;
    }
            
    /**
     * @param string $key
     * @return \PhpAbac\Model\PolicyRuleAttribute
     */
    public function removeExtraData($key)
    {
        if (isset($this->extraData[$key])) {
            unset($this->extraData[$key]);
        }
        return $this;
    }
    
    /**
     * @return array
     */
    public function getExtraData()
    {
        return $this->extraData;
    }
    
    /**
     * @param array $value
     *
     * @return static
     */
    public function setGetterParams($value)
    {
        $this->getter_params_a = $value;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getGetterParams()
    {
        return $this->getter_params_a;
    }
}
