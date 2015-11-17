<?php

namespace PhpAbac\Model;

class PolicyRule
{
    /** @var int **/
    protected $id;
    /** @var string **/
    protected $name;
    /** @var array<PhpAbac\Model\PolicyRuleAttribute> **/
    protected $policyRuleAttributes;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $updatedAt;

    public function __construct()
    {
        $this->policyRuleAttributes = [];
    }

    /**
     * @param int $id
     *
     * @return \PhpAbac\Model\PolicyRule
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * @param \DateTime $datetime
     *
     * @return \PhpAbac\Model\Attribute
     */
    public function setCreatedAt(\DateTime $datetime)
    {
        $this->createdAt = $datetime;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $datetime
     *
     * @return \PhpAbac\Model\Attribute
     */
    public function setUpdatedAt(\DateTime $datetime)
    {
        $this->updatedAt = $datetime;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
