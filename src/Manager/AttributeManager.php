<?php

namespace PhpAbac\Manager;

use PhpAbac\Model\AbstractAttribute;
use PhpAbac\Model\Attribute;
use PhpAbac\Model\EnvironmentAttribute;

class AttributeManager
{
    /** @var array **/
    private $attributes;
    
    /** @var string Prefix to add before getter name (default)'get' */
    private $getter_prefix = 'get';
    /** @var string Function to apply on the getter name ( before adding prefix ) (default)'ucfirst' */
    private $getter_name_transformation_function = 'ucfirst';
    

    /**
     * @param array $attributes
	 * @param array $options           A List of option to configure This Abac Instance
	 *                                 Options list :
	 *                                 'getter_prefix' => Prefix to add before getter name (default)'get'
	 *                                 'getter_name_transformation_function' => Function to apply on the getter name ( before adding prefix ) (default)'ucfirst'
     */
    public function __construct($attributes, $options = [])
    {
        $this->attributes = $attributes;
	
		$options = array_intersect_key( $options, array_flip( [
			'getter_prefix',
			'getter_name_transformation_function',
		] ) );
		
		foreach($options as $name => $value) {
			$this->$name = $value;
		}
    }

    /**
     * @param string $attributeId
     * @return \PhpAbac\Model\AbstractAttribute
     */
    public function getAttribute($attributeId) {
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

    /**
     * @param array $attributeData
     * @param string $property
     * @return \PhpAbac\Model\Attribute
     */
    private function getClassicAttribute($attributeData, $property) {
        return
            (new Attribute())
            ->setName($attributeData['fields'][$property]['name'])
            ->setType($attributeData['type'])
            ->setProperty($property)
            ->setSlug($this->slugify($attributeData['fields'][$property]['name']))
        ;
    }

    /**
     * @param array $attributeData
     * @param string $key
     * @return \PhpAbac\Model\EnvironmentAttribute
     */
    private function getEnvironmentAttribute($attributeData, $key) {
        return
            (new EnvironmentAttribute())
            ->setName($attributeData[$key]['name'])
            ->setType('environment')
            ->setVariableName($attributeData[$key]['variable_name'])
            ->setSlug($this->slugify($attributeData[$key]['name']))
        ;
    }

    /**
     * @param AbstractAttribute $attribute
     * @param string $attributeType
     * @param object $user
     * @param object $object
     * @return mixed
     */
    public function retrieveAttribute(AbstractAttribute $attribute, $user = null, $object = null, $getter_params = [])
    {
        switch($attribute->getType()) {
            case 'user':
                return $this->retrieveClassicAttribute($attribute, $user, $getter_params);
            case 'resource':
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
    private function retrieveClassicAttribute(Attribute $attribute, $object, $getter_params = [])
    {
        $propertyPath = explode('.', $attribute->getProperty());
        $propertyValue = $object;
        foreach($propertyPath as $property) {
	
        	
			$getter = $this->getter_prefix.call_user_func($this->getter_name_transformation_function,$property);
            // Use is_callable, instead of method_exists, to deal with __call magic method
            if(!is_callable([$propertyValue,$getter])) {
                throw new \InvalidArgumentException('There is no getter for the "'.$attribute->getProperty().'" attribute for object "'.get_class($propertyValue).'" with getter "'.$getter.'"');
            }
			if ( ( $propertyValue = call_user_func_array( [
					$propertyValue,
					$getter,
				], isset( $getter_params[ $property ] ) ? $getter_params[ $property ] : [] ) ) === null
			) {
				return null;
			}
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

    /*
     * @param string $name
     * @return string
     */
    public function slugify($name)
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
