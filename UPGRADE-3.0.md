Upgrade from 2.x to 3.0
=======================

PHP version
---------------

The minimal supported PHP version is 7.0.

Create Abac instance
--------------------

In 2.x, you were able to create an Abac instance the following way:

```php
<?php

use PhpAbac\Abac;

$abac = new Abac($configurationFiles, $cacheOptions);
```

Today, the Abac class constructor has dependencies as arguments:

```php
<?php

use PhpAbac\Abac;

$abac = new Abac($policyRuleManager, $attributeManager, $comparisonManager, $cacheManager);
```

To make life easier, a factory pattern was implemented. If you're not interested in replacing default dependencies, you can simply use:

```
<?php

use PhpAbac\AbacFactory;

$abac = AbacFactory::getAbac();
```
