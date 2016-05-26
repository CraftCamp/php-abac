<?php

namespace PhpAbac\Model;

class PolicyRule
{
    /** @var string **/
    protected $name;
    /** @var array<PhpAbac\Model\PolicyRuleAttribute> **/
    protected $policyRuleAttributes;

    public function __construct()
    {
        $this->policyRuleAttributes = [];
    }

    /**
     * @param string $name
     *
     * @return \PhpAbac\Model\PolicyRule
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param PolicyRuleAttribute $pra
     *
     * @return \PhpAbac\Model\PolicyRule
     */
    public function addPolicyRuleAttribute(PolicyRuleAttribute $pra)
    {
        if (!in_array($pra, $this->policyRuleAttributes, true)) {
            $this->policyRuleAttributes[] = $pra;
        }

        return $this;
    }

    /**
     * @param PolicyRuleAttribute $pra
     *
     * @return \PhpAbac\Model\PolicyRule
     */
    public function removePolicyRuleAttribute(PolicyRuleAttribute $pra)
    {
        if (($key = array_search($pra, $this->policyRuleAttributes)) !== false) {
            unset($this->policyRuleAttributes[$key]);
        }

        return $this;
    }

    /**
     * @return array<PhpAbac\Model\PolicyRuleAttribute>
     */
    public function getPolicyRuleAttributes()
    {
        return $this->policyRuleAttributes;
    }
}
