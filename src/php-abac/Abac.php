<?php

namespace PhpAbac;

class Abac {
    /** @var array **/
    private static $container;
    
    /**
     * @param \PDO $connection
     */
    public function __construct($connection)
    {
        self::set('policy-rule-manager', new PolicyRuleManager());
        self::set('attribute-manager', new AttributeManager());
        self::set('pdo-connection', $connection);
    }
    
    /**
     * @param string $serviceName
     * @param mixed $service
     * @param boolean $force
     * @throws \InvalidArgumentException
     */
    public static function set($serviceName, $service, $force = false) {
        if(self::has($serviceName) && $force === false) {
            throw new \InvalidArgumentException(
                "The service $serviceName is already set in PhpAbac container. ".
                'Please set $force parameter to true if you want to replace the set service'
            );
        }
        self::$container[$serviceName] = $service;
    }
    
    /**
     * @param string $serviceName
     * @return boolean
     */
    public static function has($serviceName) {
        return isset(self::$container[$serviceName]);
    }
    
    /**
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public static function get($serviceName) {
        if(!self::has($serviceName)) {
            throw new \InvalidArgumentException("The PhpAbac container has no service named $serviceName");
        }
        return self::$container[$serviceName];
    }
}