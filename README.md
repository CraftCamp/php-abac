[CraftCamp] php-abac
========

### Attribute-Based Access Control implementation library

[![Latest Stable Version](https://poser.pugx.org/craftcamp/php-abac/v/stable)](https://packagist.org/packages/craftcamp/php-abac)
[![Latest Unstable Version](https://poser.pugx.org/craftcamp/php-abac/v/unstable)](https://packagist.org/packages/craftcamp/php-abac)
[![Build Status](https://travis-ci.org/CraftCamp/php-abac.svg?branch=master)](https://travis-ci.org/CraftCamp/php-abac)
[![Code Coverage](https://scrutinizer-ci.com/g/CraftCamp/php-abac/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/CraftCamp/php-abac/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/CraftCamp/php-abac/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/CraftCamp/php-abac/?branch=master)
[![Total Downloads](https://poser.pugx.org/craftcamp/php-abac/downloads)](https://packagist.org/packages/craftcamp/php-abac)
[![License](https://poser.pugx.org/craftcamp/php-abac/license)](https://packagist.org/packages/craftcamp/php-abac)

Introduction
------------

This library is meant to implement the concept of ABAC in your PHP applications.

The concept is to manage access control using attributes : from users, from resources and environment.

It allows us to define rules based on the properties of the user object and optionally the accessed object.

These rules will be checked in your application to determine if an user is allowed to perform an action.

The following links explain what ABAC is :

* [ABAC Introduction](http://www.axiomatics.com/attribute-based-access-control.html)
* [NIST specification](http://nvlpubs.nist.gov/nistpubs/specialpublications/NIST.sp.800-162.pdf)

Installation
------------

**Using composer :**

```sh
composer require craftcamp/php-abac
```

Then you will have to configure the attributes and the rules of your application.

For more details about this, please refer to the [dedicated documentation](doc/configuration.md)

Documentation
------------

* [Configuration](doc/configuration.md)
* [Access-control](doc/access-control.md)
* [Comparisons](doc/comparisons.md)
* [Caching](doc/caching.md)

Usage Examples
-------------

**Example with only user attributes defined in the rule**

We have in this example a single object, representing the current user.

This object have properties, with getter methods to access the values.

For example, we can code :

```php
<?php

use PhpAbac\AbacFactory;

class User{
    protected $id;

    protected $isBanned;

    public function getId() {
        return $this->id;
    }

    public function setIsBanned($isBanned) {
        $this->isBanned = $isBanned;

        return $this;
    }

    public function getIsBanned() {
        return $this->isBanned;
    }
}

$user = new User();
$user->setIsBanned(true);

$abac = AbacFactory::getAbac([
    'policy_rule_configuration.yml'
]);
$abac->enforce('create-group', $user);
```
The attributes checked by the rule can be :

|User|
|-----|
|isBanned = false|

**Example with both user and object attributes**
```php
use PhpAbac\AbacFactory;

$abac = AbacFactory::getAbac([
    'policy_rule_configuration.yml'
]);
$check = $abac->enforce('read-public-group', $user, $group);
```
The checked attributes can be :

|User|Group|
|-----|----|
|isBanned = 0|isActive = 1|
||isPublic = 1|

**Example with dynamic attributes**
```php
<?php

use PhpAbac\AbacFactory;

$abac = AbacFactory::getAbac([
    'policy_rule_configuration.yml'
]);
$check = $abac->enforce('edit-group', $user, $group, [
    'dynamic-attributes' => [
        'group-owner' => $user->getId()
    ]
]);
```

**Example with referenced attributes**

The configuration shall be :

```yaml
attributes:
    group:
        class: MyApp\Model\Group
        type: resource
        fields:
            author.id:
                name: Author ID
    app_user:
        class: MyApp\Model\User
        type: user
        fields:
            id:
                name: User ID

rules:
    remove-group:
        attributes:
            app_user.id:
                comparison: object
                comparison_type: isFieldEqual
                value: group.author.id
```
And then the code :

```php
<?php

use PhpAbac\AbacFactory;

$abac = AbacFactory::getAbac([
    'policy_rule_configuration.yml'
]);
$check = $abac->enforce('remove-group', $user, $group);
```


**Example with cache**
```php
$check = $abac->enforce('edit-group', $user, $group, [
    'cache_result' => true,
    'cache_ttl' => 3600, // Time To Live in seconds
    'cache_driver' => 'memory' // memory is the default driver, you can avoid this option
]);
```

**Example with multiple rules (ruleSet) for an unique rule.**
Each rule are tested and the treatment stop when the first rule of the ruleSet allow access

The configuration shall be (alcoolaw.yml):

```yaml
attributes:
    main_user:
        class: PhpAbac\Example\User
        type: user
        fields:
            age:
                name: Age
            country:
                name: Code ISO du pays
rules:
    alcoollaw:
        -
            attributes:
                main_user.age:
                    comparison_type: numeric
                    comparison: isGreaterThan
                    value: 18
                main_user.country:
                    comparison_type: string
                    comparison: isEqual
                    value: FR
        -
            attributes:
                main_user.age:
                    comparison_type: numeric
                    comparison: isGreaterThan
                    value: 21
                main_user.country:
                    comparison_type: string
                    comparison: isNotEqual
                    value: FR

```

And then the code :

```php
<?php

use PhpAbac\AbacFactory;

$abac = AbacFactory::getAbac([
    'alcoollaw.yml'
]);
$check = $abac->enforce('alcoollaw', $user);
```

**Example with rules root directory passed to Abac class.**
This feature allow to give a policy definition rules directory path directly to the Abac class without adding to all files :
 
Considering we have 3 yaml files :
- rest/conf/policy/user_def.yml
- rest/conf/policy/gunlaw.yml 

The php code can be :
```php
<?php

use PhpAbac\AbacFactory;

$abac = AbacFactory::getAbac([
    'user_def.yml',
    'gunlaw.yml',
],[],'rest/conf/policy/');
$check = $abac->enforce('gunlaw', $user);
 
```

Contribute
----------

If you want to contribute, don't hesitate to fork the library and submit Pull Requests.

You can also report issues, suggest enhancements, feel free to give advices and your feedback about this library.

It's not finished yet, there's still a lot of features to implement to make it better. If you want to be a part of this library improvement, let us know  !

See also
--------

* [Symfony bundle to support this library](https://github.com/CraftCamp/abac-bundle)
