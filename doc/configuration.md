Configuration
=============

When you initialize the PHP ABAC library, you can pass multiple configuration files as arguments.

These files will be parsed and the data will be extracted.

This way, you can avoid long configuration files in your application and use several files instead.

The configurations will be merged.


```php
<?php
    use PhpAbac\AbacFactory;

    $abac = AbacFactory::getAbac([
        __DIR__ . '/config/driving_licenses_policy_rules.yml',
        __DIR__ . '/config/vehicles_policy_rules.yml',
        __DIR__ . '/config/attributes.yml',
    ]);
    $abac->enforce('vehicle-homologation', $user, $vehicle);
```

Configuration file can be yaml or json files, and format can be mixed.  

```php
<?php
    use PhpAbac\AbacFactory;

    $abac = AbacFactory::getAbac([
        __DIR__ . '/config/driving_licenses_policy_rules.json',
        __DIR__ . '/config/vehicles_policy_rules.yml',
        __DIR__ . '/config/attributes.json',
    ]);
    $abac->enforce('vehicle-homologation', $user, $vehicle);
```

If all configuration file are in the same folder, you can add this folder in 3th paramter of Abac contructor.
 
```php
<?php
    use PhpAbac\AbacFactory;

    $abac = AbacFactory::getAbac([
        'driving_licenses_policy_rules.json',
        'vehicles_policy_rules.yml',
        'attributes.json',
    ],[],__DIR__ . '/config/');
    $abac->enforce('vehicle-homologation', $user, $vehicle);
```

Configuration Options
---------------------
Abac constructor allow a 4th parameter called options : 
```php 
public function __construct( $configPaths, $cacheOptions = [], $configPaths_root = null, $options = [] );
```

This parameter must be an array and can contains this options : 
- getter_prefix (default='get') : Prefix to add before getter name
- getter_name_transformation_function (default='ucfirst') : Function to apply on the getter name ( before adding prefix ) 


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

Extra Data
===========

Sometimes, you will have to do more complex comparisons.

The basic configuration will not be sufficient to perform these comparisons.

There is more advanced configuration properties available to make it.

For example, we want to check if an user has a visa to travel to Germany.

Each user can have several visas, we need to check that the visas collection contains a visa with the proper attributes :

```yaml
# abac_config.yml
attributes:
    visa:
        class: PhpAbac\Example\Visa
        type: resource
        fields:
            country:
                name: Pays
            lastRenewal:
                name: Dernier renouvellement
rules:
    travel-to-germany:
        attributes:
            main_user.visas:
                comparison_type: array
                comparison: contains
                with:
                    visa.country:
                        comparison_type: string
                        comparison: isEqual
                        value: DE
                    visa.lastRenewal:
                        comparison_type: datetime
                        comparison: isMoreRecentThan
                        value: -1Y
```

There is no value configured for the attribute, but a ``with`` property.

This property contains an array of attributes to check.

Then you can use ABAC the same way as before :

```php
$isGranted = $abac->enforce('travel-to-germany', $user);
```

Chained Attributes
==================

If you want to check an attribute which is an already configured attribute property, you can use a special syntax to declare chained attributes.

With the previous extra data example, let's create a Country model class, which is a Visa property.

The previous configuration would be updated to :

```yaml
# abac_config.yml
attributes:
    visa:
        class: PhpAbac\Example\Visa
        type: resource
        fields:
            country.code:
                name: Code Pays
            lastRenewal:
                name: Dernier renouvellement

    country:
        class: PhpAbac\Example\Country
        type: resource
        fields:
            code:
                name: Code

rules:
    travel-to-germany:
        attributes:
            main_user.visas:
                comparison_type: array
                comparison: contains
                with:
                    visa.country.code:
                        comparison_type: string
                        comparison: isEqual
                        value: DE
                    visa.lastRenewal:
                        comparison_type: datetime
                        comparison: isMoreRecentThan
                        value: -1Y
```

This way, the library will perform something similar to :

```php
$visa->getCountry()->getCode() === 'DE';
```

Multiple Attributes rules for an unique named rule.
===================================================
The first rules that return allow acces stop the check process and return true. 

If we update the previous configuration to :
```yaml
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
            countryCode:
                name: ISO code du pays
                
rules:
    travel-to-germany:
    # First test, User is a German User ?
        -
            attributes:
                main_user.countryCode:
                    comparison_type: string
                    comparison: isEqual
                    value: DE
    # Or Second test, User have a visa for Germany
        -
            attributes:
                main_user.visas:
                    comparison_type: array
                    comparison: contains
                    with:
                        visa.country.code:
                            comparison_type: string
                            comparison: isEqual
                            value: DE
                        visa.lastRenewal:
                            comparison_type: datetime
                            comparison: isMoreRecentThan
                            value: -1Y
```


Import property 
=================

The better way to define all attributes and rules is to make each definition in a specific file. Is more convenient to understand each rule an each objet{resource/user} definition. 

file : users/main_user.yml
```yaml 
---
attributes:
    main_user:
        class: PhpAbac\Example\User
        type: user
        fields:
            id:
                name: ID
            age:
                name: Age
``` 


file : travel-to-foreign-country.yml
```yaml 
---
'@import':
    - users/main_user.yml

rules:
    travel:
        attributes:
            main_user.age:
                comparison_type: numeric
                comparison: isGreaterThan
                value: 18
```



Used Getter extended paramters 
==============================

Sometimes, you need to call getter with parameters. 

it's possible by adding getter_params list in attributes rules specification. 

```yaml
---
rules:
    travel-to-foreign-country:
        attributes:
            main_user.age:
                comparison_type: numeric
                comparison: isGreaterThan
                value: 18
            main_user.visa:
                comparison_type: array
                comparison: contains
                getter_params:
                  visa:
                    -
                      param_name: '@country_code'
                      param_value: country.code
                # The executed code will be : $main_user->getVisa($country->getCode)
                # If you want only simple value, remove @ in param_name value. 
                with:
                    visa.lastRenewal:
                        comparison_type: datetime
                        comparison: isMoreRecentThan
                        value: -1Y
```