<?php

namespace PhpAbac\Manager;

use PhpAbac\Repository\AttributeRepository;
use PhpAbac\Model\AbstractAttribute;
use PhpAbac\Model\Attribute;

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
     * @param string            $attributeType
     * @param int               $userId
     * @param int               $objectId
     */
    public function retrieveAttribute(AbstractAttribute $attribute, $attributeType, $userId, $objectId)
    {
        // The switch is important.
        // Even if we call the same method for the two first cases,
        // the given argument isn't the same.
        switch ($attributeType) {
            case 'user':
                $this->repository->retrieveAttribute($attribute, $userId);
                break;
            case 'object':
                $this->repository->retrieveAttribute($attribute, $objectId);
                break;
            case 'environment':
                $attribute->setValue(getenv($attribute->getVariableName()));
                break;
        }
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
