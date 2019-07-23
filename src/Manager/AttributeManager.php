<?php

namespace PhpAbac\Manager;

use InvalidArgumentException;
use PhpAbac\Configuration\ConfigurationInterface;
use PhpAbac\Model\AbstractAttribute;
use PhpAbac\Model\Attribute;
use PhpAbac\Model\EnvironmentAttribute;

class AttributeManager implements AttributeManagerInterface
{
    /** @var array **/
    private $attributes;
    /** @var string Prefix to add before getter name (default)'get' */
    private $getter_prefix = 'get';
    /** @var string Function to apply on the getter name ( before adding prefix ) (default)'ucfirst' */
    private $getter_name_transformation_function = 'ucfirst';
    
    /**
     *  A List of option to configure This Abac Instance
     *  Options list :
     *    'getter_prefix' => Prefix to add before getter name (default)'get'
     *    'getter_name_transformation_function' => Function to apply on the getter name ( before adding prefix ) (default)'ucfirst'
     *
     * @param ConfigurationInterface $configuration
     * @param array $options
     */
    public function __construct(ConfigurationInterface $configuration, array $options = [])
    {
        $this->attributes = $configuration->getAttributes();
    
        $options = array_intersect_key($options, array_flip([
            'getter_prefix',
            'getter_name_transformation_function',
        ]));
        
        foreach ($options as $name => $value) {
            $this->$name = $value;
        }
    }

    public function getAttribute(string $attributeId): AbstractAttribute
    {
        $attributeKeys = explode('.', $attributeId);
        // The first element will be the attribute ID, then the field ID
        $attributeId = array_shift($attributeKeys);
        $attributeName = implode('.', $attributeKeys);
        // The field ID is also the attribute object property
        $attributeData = $this->attributes[$attributeId];
        return
            ($attributeId === 'environment')
            ? $this->getEnvironmentAttribute($attributeData, $attributeName)
            : $this->getClassicAttribute($attributeData, $attributeName)
        ;
    }

    private function getClassicAttribute(array $attributeData, string $property): Attribute
    {
        return
            (new Attribute())
            ->setName($attributeData['fields'][$property]['name'])
            ->setType($attributeData['type'])
            ->setProperty($property)
            ->setSlug($this->slugify($attributeData['fields'][$property]['name']))
        ;
    }

    private function getEnvironmentAttribute(array $attributeData, string $key): EnvironmentAttribute
    {
        return
            (new EnvironmentAttribute())
            ->setName($attributeData[$key]['name'])
            ->setType('environment')
            ->setVariableName($attributeData[$key]['variable_name'])
            ->setSlug($this->slugify($attributeData[$key]['name']))
        ;
    }

    public function retrieveAttribute(AbstractAttribute $attribute, $user = null, $object = null, array $getter_params = [])
    {
        switch ($attribute->getType()) {
            case 'user':
                return $this->retrieveClassicAttribute($attribute, $user, $getter_params);
            case 'resource':
                return $this->retrieveClassicAttribute($attribute, $object);
            case 'environment':
                return $this->retrieveEnvironmentAttribute($attribute);
        }
    }

    private function retrieveClassicAttribute(Attribute $attribute, $object, array $getter_params = [])
    {
        $propertyPath = explode('.', $attribute->getProperty());
        $propertyValue = $object;
        foreach ($propertyPath as $property) {
            $getter = $this->getter_prefix.call_user_func($this->getter_name_transformation_function, $property);
            // Use is_callable, instead of method_exists, to deal with __call magic method
            if (!is_callable([$propertyValue,$getter])) {
                throw new InvalidArgumentException('There is no getter for the "'.$attribute->getProperty().'" attribute for object "'.get_class($propertyValue).'" with getter "'.$getter.'"');
            }
            if (($propertyValue = call_user_func_array([
                    $propertyValue,
                    $getter,
                ], isset($getter_params[ $property ]) ? $getter_params[ $property ] : [])) === null
            ) {
                return null;
            }
        }
        return $propertyValue;
    }

    private function retrieveEnvironmentAttribute(EnvironmentAttribute $attribute)
    {
        return getenv($attribute->getVariableName());
    }

    public function slugify(string $name): string
    {
        // replace non letter or digits by -
        $name = trim(preg_replace('~[^\\pL\d]+~u', '-', $name), '-');
        // transliterate
        if (function_exists('iconv')) {
            $name = iconv('utf-8', 'us-ascii//TRANSLIT', $name);
        }
        // remove unwanted characters
        $name = preg_replace('~[^-\w]+~', '', strtolower($name));
        if (empty($name)) {
            return 'n-a';
        }
        return $name;
    }
}
