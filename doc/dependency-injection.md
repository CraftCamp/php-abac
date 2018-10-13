DependencyInjection
===================

In order to allow you to extend the library, a proper dependency injection was set.

You're able to replace each component of the Abac library.

The normal way to get the library and enforce your rules is:

```php
<?php
    use PhpAbac\AbacFactory;

    $abac = AbacFactory::getAbac();
```

This factory method creates an ```Abac``` instance with its default dependencies.

But you can replace one of them with your own component.

You just have to make your new component implement the dependency interface.

```php
<?php
// src/my-app/Cache/MyCacheManager.php

use PhpAbac\Manager\CacheManagerInterface;

class MyCacheManager implements CacheManagerInterface
{
    // implement the interface methods
}
```

```php
<?php
// src/my-app/Controller/MyController.php

use PhpAbac\AbacFactory;

$cacheManager = new MyCacheManager();

AbacFactory::setCacheManager($cacheManager);
AbacFactory::getAbac();
```

Here are the available interfaces:

* PhpAbac\Configuration\ConfigurationInterface
* PhpAbac\Manager\PolicyRuleManagerInterface
* PhpAbac\Manager\AttributeManagerInterface
* PhpAbac\Manager\CacheManagerInterface
* PhpAbac\Manager\ComparisonManagerInterface

Each of this component has a dedicated setter in the factory.