<?php

namespace PhpAbac\Model;

class PolicyRuleAttribute
{
    /** @var PhpAbac\Model\AbstractAttribute **/
    protected $attribute;
    /** @var string **/
    protected $type;
    /** @var string **/
    protected $comparisonType;
    /** @var string **/
    protected $comparison;
    /** @var mixed **/
    protected $value;

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
     * @param string $type
     *
     * @return \PhpAbac\Model\PolicyRuleAttribute
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
}
