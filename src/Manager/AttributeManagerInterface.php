<?php

namespace PhpAbac\Manager;

use PhpAbac\Model\AbstractAttribute;

interface AttributeManagerInterface
{
    public function getAttribute(string $attributeId): AbstractAttribute;

    public function retrieveAttribute(AbstractAttribute $attribute, $user = null, $object = null, array $getter_params = []);
}
