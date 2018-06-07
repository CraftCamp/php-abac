<?php

namespace PhpAbac\Model;

class PolicyRule
{
    /** @var string **/
    protected $name;
    /** @var array<PolicyRuleAttribute> **/
    protected $policyRuleAttributes;

    public function __construct()
    {
        $this->policyRuleAttributes = [];
    }

    public function setName(string $name): PolicyRule
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addPolicyRuleAttribute(PolicyRuleAttribute $pra): PolicyRule
    {
        if (!in_array($pra, $this->policyRuleAttributes, true)) {
            $this->policyRuleAttributes[] = $pra;
        }
        return $this;
    }

    public function removePolicyRuleAttribute(PolicyRuleAttribute $pra): PolicyRule
    {
        if (($key = array_search($pra, $this->policyRuleAttributes)) !== false) {
            unset($this->policyRuleAttributes[$key]);
        }

        return $this;
    }

    public function getPolicyRuleAttributes(): array
    {
        return $this->policyRuleAttributes;
    }
}
