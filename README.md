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

It allows us to define rules based on multiple attributes. These rules will be checked in your application to determine if an user is allowed to perform an action.

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

To initialize the library tables in your database, there is a SQL file located in the ``sql/`` folder. This file will create the tables used by php-abac.

Usage
---

**Example with only user attributes defined in the rule**

```php
<?php

use PhpAbac\Abac;

$abac = new Abac($pdoConnection);
$abac->enforce('create-group', $userId);
```
The checked attributes can be :

|User|
|-----|
|is_banned = 0|

**Example with both user and object attributes**
```php
use PhpAbac\Abac;

$abac = new Abac($pdoConnection);
$check = $abac->enforce('read-public-group', $userId, $groupId);
```
The checked attributes can be :

|User|Group|
|-----|----|
|is_banned = 0|is_active = 1|
||is_public = 1|

**Example with dynamic attributes**
```php
<?php

use PhpAbac\Abac;

$abac = new Abac($pdoConnection);
$check = $abac->enforce('edit-group', $userId, $groupId, [
	'group-owner' => $userId
]);

```

Documentation
-------

* [Policy Rules](doc/policy_rules.md)
* [Attributes](doc/attributes.md)
* [Comparisons](doc/comparisons.md)
* [Access-control](doc/access-control.md)

Contribute
-------

If you want to contribute, don't hesitate to fork the library and submit Pull Requests.

You can also report issues, suggest enhancements, feel free to give advices and your feedback about this library.

It's not finished yet, there's still a lot of features to implement to make it better. If you want to be a part of this library improvement, let us know  !
