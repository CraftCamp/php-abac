Access-Control
==============

Introduction
------------

This library is meant to perform access control with precise logic.

Using policy rules, we can analyze users, resources and environment attributes to determine if an user is able to perform an action.

Once our policy rules are defined, we can simply check if a policy rule is enforced in a given case.

Usage
---

```php
use PhpAbac\Abac;

$check = $abac->enforce('medical-reports-access', $user, $report);
```

```$check``` have two possible values :

* ```true```, meaning that all the policy rules required attributes are matched for the given user and resource.
* An array of slugs, associated to the attributes which did not match.

Dynamic Attributes
------------------

In some cases, an attribute won't be expected to match a static value, but a dynamic value depending on the case.

In these cases, the library allows you to give an array as fourth argument of the enforce() method.

This associative array will contain the targetted attribute's slug as key and the dynamic value.

For example, it can be useful to check the ownership of a resource :

```php
use PhpAbac\Abac;

$check = $abac->enforce('medical-reports-access', $user, $report, [
    'dynamic-attributes' => [
        'report-author' => $user->getId()
    ]
]);
```

Be careful, the key of your dynamic attribute is the **slug** of the attribute's name, not its configuration ID.

To define an attribute as dynamic, we can write the following code in the configuration file :

```yaml
attributes:
    medical_report:
        class: MySuperVeterinary\Model\MedicalReport
        type: resource
        fields:
            author.id:
                name: Report Author
rules:
    medical-reports-access:
        attributes:
            medical_report.author.id:
                comparison_type: numeric
                comparison: isEqual
                value: dynamic
```

Comparison by reference
-----------------------

In some cases, you shall want to compare users and resources between themselves.

This is possible with the proper configuration to check if a resource field is equal to an user's.

Let's refactor the previous example.

```yaml
attributes:
    main_user:
        class: MySuperVeterinary\Model\User
        type: user
        fields:
            id:
                name: User ID
    medical_report:
        class: MySuperVeterinary\Model\MedicalReport
        type: resource
        fields:
            author.id:
                name: Report Author
rules:
    medical-reports-access:
        attributes:
            medical_report.author.id:
                comparison_type: user
                comparison: isFieldEqual
                value: main_user.id
```

This way, you can call the ```enforce``` method without dynamic attributes, the ```User``` and the ```MedicalReport``` fields will be compared one another.

The same can be done with resources :

```yaml
attributes:
    main_user:
        class: MyTravelApp\Model\User
        type: user
        fields:
            id:
                name: User ID
            nationality:
                name: Nationality
    country:
        class: MyTravelApp\Model\MedicalReport
        type: resource
        fields:
            name:
                name: Country name
            code:
                name: Country code

rules:
    nationality-access:
        attributes:
            main_user.nationality:
                comparison_type: object
                comparison: isFieldEqual
                value: country.code
```

You just have to call :

```php
$isAllowed = $abac->enforce('nationality-access', $person, $country);
```

Cache
-----------------

This library implements cache using PSR-6 specification.

To enable cache for a specific call of the enforce method, add the following options :

```php
$check = $abac->enforce('medical-reports-access', $user, $report, [
    'dynamic-attributes' => [
        'report-author' => $user->getId()
    ],
    'cache_result' => true, // enable cache
    'cache_ttl' => 60, // Time to live in seconds, default is one hour
    'cache_driver' => 'memory' // Default is memory
]);
```

With this, if you call this method again with the same $user and $report, the previous result will be returned.

Available cache drivers :

* ``memory`` : This cache is stored in the library RAM. It will be erased after the script execution.
