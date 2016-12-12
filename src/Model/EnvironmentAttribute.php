<?php

namespace PhpAbac\Model;

class EnvironmentAttribute extends AbstractAttribute
{
    /** @var string **/
    protected $variableName;

    /**
     * @param string $variableName
     *
     * @return static
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
