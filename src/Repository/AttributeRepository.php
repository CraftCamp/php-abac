<?php

namespace PhpAbac\Repository;

use PhpAbac\Model\Attribute;
use PhpAbac\Model\EnvironmentAttribute;

class AttributeRepository extends Repository
{
    /**
     * @param int $attributeId
     *
     * @return Attribute
     */
    public function findAttribute($attributeId)
    {
        $data = $this->query(
            'SELECT ad.name, ad.slug, a.property, ad.created_at, ad.updated_at '.
            'FROM abac_attributes_data ad INNER JOIN abac_attributes a ON a.id = ad.id WHERE ad.id = :id'
        , ['id' => $attributeId])->fetch();
        return
            (new Attribute())
            ->setName($data['name'])
            ->setSlug($data['slug'])
            ->setProperty($data['property'])
            ->setCreatedAt($data['created_at'])
            ->setUpdatedAt($data['updated_at'])
        ;
    }

    /**
     * @param Attribute $attribute
     */
    public function createAttribute(Attribute $attribute)
    {
        $datetime = new \DateTime();
        $formattedDatetime = $datetime->format('Y-m-d H:i:s');
        $slug = $this->slugify($attribute->getName());

        $this->insert(
            'INSERT INTO abac_attributes_data (created_at, updated_at, name, slug) ' .
            'VALUES(:created_at, :updated_at, :name, :slug);', [
            'name' => $attribute->getName(),
            'slug' => $slug,
            'created_at' => $formattedDatetime,
            'updated_at' => $formattedDatetime,
        ]);
        $this->insert(
            'INSERT INTO abac_attributes (id, property) VALUES(:id, :property);', [
            'id' => $this->connection->lastInsertId('abac_attributes_data'),
            'property' => $attribute->getProperty(),
        ]);

        $attribute
            ->setId($this->connection->lastInsertId('abac_attributes'))
            ->setSlug($slug)
            ->setCreatedAt($datetime)
            ->setUpdatedAt($datetime)
        ;
    }

    /**
     * @param EnvironmentAttribute $attribute
     */
    public function createEnvironmentAttribute(EnvironmentAttribute $attribute)
    {
        $datetime = new \DateTime();
        $formattedDatetime = $datetime->format('Y-m-d H:i:s');
        $slug = $this->slugify($attribute->getName());

        $this->insert(
            'INSERT INTO abac_attributes_data (created_at, updated_at, name, slug) ' .
            'VALUES(:created_at, :updated_at, :name, :slug);', [
            'name' => $attribute->getName(),
            'slug' => $slug,
            'created_at' => $formattedDatetime,
            'updated_at' => $formattedDatetime,
        ]);

        $id = $this->connection->lastInsertId('abac_attributes_data');

        $this->insert(
            'INSERT INTO abac_environment_attributes (id, variable_name) ' .
            'VALUES(:id, :variable_name);', [
            'id' => $id,
            'variable_name' => $attribute->getVariableName(),
        ]);

        $attribute
            ->setId($this->connection->lastInsertId('abac_attributes'))
            ->setSlug($slug)
            ->setCreatedAt($datetime)
            ->setUpdatedAt($datetime)
        ;
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
