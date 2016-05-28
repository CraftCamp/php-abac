Configuration
=============

When you initialize the PHP ABAC library, you can pass multiple configuration files as arguments.

These files will be parsed and the data will be extracted.

This way, you can avoid long configuration files in your application and use several files instead.

The configurations will be merged.

```php
<?php
    $abac = new Abac([
        __DIR__ . '/config/driving_licenses_policy_rules.yml',
        __DIR__ . '/config/vehicles_policy_rules.yml',
        __DIR__ . '/config/attributes.yml',
    ]);
    $abac->enforce('vehicle-homologation', $user, $vehicle);
```

Attributes
----------

Attributes are object properties mapped to be used in the rule check.

The are two types of attributes :

* **Object attributes**: these are the fields of your users and resources classes, like described above.
To declare an object property as an attribute, it must have a getter. For example, an user with an ``$age`` property must have a ``getAge()`` public method.
* **Environment attributes**: These attributes are accessed with the [``getenv()``](http://php.net/manual/fr/function.getenv.php) PHP native function.
It allows your rules to check environment variables along with the object attributes.

This is an example of configured attributes in a YAML file :

```yaml
---
attributes:
    main_user:
        class: PhpAbac\Example\User
        type: user
        fields:
            age:
                name: Age
            parentNationality:
                name: Nationalité des parents
            hasDoneJapd:
                name: JAPD
            hasDrivingLicense:
                name: Permis de conduire
            
    vehicle:
        class: PhpAbac\Example\Vehicle
        type: resource
        fields:
            origin:
                name: Origine
            owner.id:
                name: Propriétaire
            manufactureDate:
                name: Date de sortie d'usine
            lastTechnicalReviewDate:
                name: Dernière révision technique
        
    environment:
        service_state:
            name: Statut du service
            variable_name: SERVICE_STATE
```

The ```class``` key is not used yet, but will be used soon to make a single rule securing different resources.

The ```type``` key has two values : ``user`` and ``resource``.

Rules
-----

To define a rule, you must give it a name, and configure the checked attributes.

The attributes are already defined, you just have to link it to your rules,

and add data about the comparison which will be performed by the library to determine if the user have access to the given resource.

For example, you can have the following configuration :

```yaml
---
rules:
    vehicle-homologation:
        attributes:
            main_user.hasDrivingLicense:
                comparison_type: boolean
                comparison: boolAnd
                value: true
            vehicle.lastTechnicalReviewDate:
                comparison_type: datetime
                comparison: isMoreRecentThan
                value: -2Y
            vehicle.manufactureDate:
                comparison_type: datetime
                comparison: isMoreRecentThan
                value: -25Y
            vehicle.origin:
                comparison_type: array
                comparison: isIn
                value: ["FR", "DE", "IT", "L", "GB", "P", "ES", "NL", "B"]
            environment.service_state:
                comparison_type: string
                comparison: isEqual
                value: OPEN
```

A [list](comparisons.md) of the available comparisons is created and will be updated with new comparisons.