[Kilix] php-abac
========

### Attribute-Based Access Control implementation library

[![Latest Stable Version](https://poser.pugx.org/kilix/php-abac/v/stable)](https://packagist.org/packages/kilix/php-abac)
[![Latest Unstable Version](https://poser.pugx.org/kilix/php-abac/v/unstable)](https://packagist.org/packages/kilix/php-abac)
[![Build Status](https://travis-ci.org/Kilix/php-abac.svg?branch=master)](https://travis-ci.org/Kilix/php-abac)
[![Code Coverage](https://scrutinizer-ci.com/g/Kilix/php-abac/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Kilix/php-abac/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Kilix/php-abac/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Kilix/php-abac/?branch=master)
[![Total Downloads](https://poser.pugx.org/kilix/php-abac/downloads)](https://packagist.org/packages/kilix/php-abac)
[![License](https://poser.pugx.org/kilix/php-abac/license)](https://packagist.org/packages/kilix/php-abac)

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

Write the following line in your composer.json file :

```json
"require" : {
    "kilix/php-abac": "dev-master"
}
```

Then just do :

```sh
composer install
```

Then you will have to configure the attributes and the rules of your application.

For more details about this, please refer to the [dedicated documentation](doc/configuration.md)

Documentation
------------

* [Configuration](doc/configuration.md)
* [Access-control](doc/access-control.md)
* [Comparisons](doc/comparisons.md)

Usage Examples
-------------

**Example with only user attributes defined in the rule**

We have in this example a single object, representing the current user.

This object have properties, with getter methods to access the values.

For example, we can code :

```php
<?php

use PhpAbac\Abac;

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

$abac = new Abac($pdoConnection);
$abac->enforce('create-group', $user);
```
The attributes checked by the rule can be :

|User|
|-----|
|isBanned = false|

**Example with both user and object attributes**
```php
use PhpAbac\Abac;

$abac = new Abac($pdoConnection);
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

use PhpAbac\Abac;

$abac = new Abac($pdoConnection);
$check = $abac->enforce('edit-group', $user, $group, [
    'dynamic-attributes' => [
        'group-owner' => $user->getId()
    ]
]);
```

**Example with cache**
```php
$check = $abac->enforce('edit-group', $user, $group, [
    'cache_result' => true,
    'cache_ttl' => 3600, // Time To Live in seconds
    'cache_driver' => 'memory' // memory is the default driver, you can avoid this option
]);
```

Contribute
-------

If you want to contribute, don't hesitate to fork the library and submit Pull Requests.

You can also report issues, suggest enhancements, feel free to give advices and your feedback about this library.

It's not finished yet, there's still a lot of features to implement to make it better. If you want to be a part of this library improvement, let us know  !
