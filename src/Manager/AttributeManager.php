<?php

namespace PhpAbac\Manager;

use PhpAbac\Repository\AttributeRepository;
use PhpAbac\Model\AbstractAttribute;
use PhpAbac\Model\Attribute;
use PhpAbac\Model\EnvironmentAttribute;

class AttributeManager
{
    /** @var AttributeRepository **/
    protected $repository;

    public function __construct()
    {
        $this->repository = new AttributeRepository();
    }

    /**
     * @param AbstractAttribute $attribute
     *
     * @return AbstractAttribute
     */
    public function create(AbstractAttribute $attribute)
    {
        if ($attribute instanceof Attribute) {
            return $this->repository->createAttribute($attribute);
        }

        return $this->repository->createEnvironmentAttribute($attribute);
    }

    /**
     * @param AbstractAttribute $attribute
     * @param string $attributeType
     * @param object $user
     * @param object $object
     * @return mixed
     */
    public function retrieveAttribute(AbstractAttribute $attribute, $attributeType, $user, $object)
    {
        switch($attributeType) {
            case 'user':
                return $this->retrieveClassicAttribute($attribute, $user);
            case 'object':
                return $this->retrieveClassicAttribute($attribute, $object);
            case 'environment':
                return $this->retrieveEnvironmentAttribute($attribute);
        }
    }

    /**
     * @param Attribute $attribute
     * @param object $object
     * @return mixed
     */
    private function retrieveClassicAttribute(Attribute $attribute, $object)
    {
        $propertyPath = explode('.', $attribute->getColumn());
        $propertyValue = $object;
        foreach($propertyPath as $property) {
            $getter = 'get'.ucfirst($property);
            if(!method_exists($propertyValue, $getter)) {
                throw new \InvalidArgumentException('There is no getter for the "'.$attribute->getColumn().'" attribute for object "'.get_class($propertyValue).'"');
            }
            $propertyValue = $propertyValue->{$getter}();
        }
        return $propertyValue;
    }
    
    /**
     * 
     * @param \PhpAbac\Model\EnvironmentAttribute $attribute
     * @return mixed
     */
    private function retrieveEnvironmentAttribute(EnvironmentAttribute $attribute) {
        return getenv($attribute->getVariableName());
    }

    /**
     * @param string $slug
     * @param array  $dynamicAttributes
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getDynamicAttribute($slug, $dynamicAttributes = [])
    {
        if (!isset($dynamicAttributes[$slug])) {
            throw new \InvalidArgumentException('The "'.$slug.'" attribute is dynamic and its value must be given');
        }
        return $dynamicAttributes[$slug];
    }
}
