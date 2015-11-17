<?php

namespace PhpAbac\Model;

class EnvironmentAttribute extends AbstractAttribute
{
    /** @var string **/
    protected $variableName;

    /**
     * @param string $variableName
     *
     * @return \PhpAbac\Model\EnvironmentAttribute
     */
    public function setVariableName($variableName)
    {
        $this->variableName = $variableName;

        return $this;
    }

    /**
     * @return string
     */
    public function getVariableName()
    {
        return $this->variableName;
    }
}
