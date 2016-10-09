Caching
=======

To cache access control policy rule result, you can use several PSR-6 compliant drivers.

The default one is ``memory``, which will set the results in RAM.

The list of implemented drivers are here, do not hesitate to implement your own and make a Pull Request !

Usage
-------

Some of the caching options are arguments of the ```enforce``` method.

New options can be configured in the Abac ```__construct``` method as well, like the filesystem root for your cache files.

**WARNING:** For now, do not set an ending slash in the cache_folder value.

```php
$abac = new Abac(['policy_rules.yml', [
    'cache_folder' => __DIR__ . '/cache'
]);
$abac->enforce('my_rule', $user, $resource, [
    'cache_result' => true,
    'cache_ttl' => 3600,
    'cache_driver' => 'text'
])
```

In the next versions, some of these options will be put in the ``Abac`` constructor, the ``enforce`` method will be allowed to do an override of these values.

Drivers
-------

### Memory

This is the default one. It will store the results of the ```enforce``` method in RAM.

### Text

This is a basic filesystem caching driver, which will put the results and the expiration date in a .txt file.
