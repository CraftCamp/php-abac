<?php

namespace PhpAbac\Manager;

use PhpAbac\Model\{
    AbstractAttribute,
    Attribute,
    EnvironmentAttribute
};

interface AttributeManager
{
    abstract public function getAttribute(string $attributeId): AbstractAttribute;

    abstract protected function getClassicAttribute(array $attributeData, string $property): Attribute;

    abstract protected function getEnvironmentAttribute(array $attributeData, string $key): EnvironmentAttribute;

    abstract public function retrieveAttribute(AbstractAttribute $attribute, $user = null, $object = null, array $getter_params = []);

    abstract protected function retrieveClassicAttribute(Attribute $attribute, $object, array $getter_params = []);

    abstract protected function retrieveEnvironmentAttribute(EnvironmentAttribute $attribute);

    abstract public function slugify(string $name): string;
}
