<?php

namespace PhpAbac\Model;

class EnvironmentAttribute extends AbstractAttribute
{
    /** @var string **/
    protected $variableName;

    public function setVariableName(string $variableName): EnvironmentAttribute
    {
        $this->variableName = $variableName;

        return $this;
    }

    public function getVariableName(): string
    {
        return $this->variableName;
    }
}
