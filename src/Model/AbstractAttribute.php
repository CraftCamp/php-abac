<?php

namespace PhpAbac\Model;

abstract class AbstractAttribute
{
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $type;
    /** @var string **/
    protected $slug;
    /** @var mixed **/
    protected $value;

    public function setName(string $name): AbstractAttribute
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setType(string $type): AbstractAttribute
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setSlug(string $slug): AbstractAttribute
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setValue($value): AbstractAttribute
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }
}
