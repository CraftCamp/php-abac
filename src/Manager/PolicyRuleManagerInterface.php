<?php

namespace PhpAbac\Manager;

interface PolicyRuleManagerInterface
{
    public function getRule(string $ruleName, $user, $resource): array;
    
    public function processRuleAttributes(array $attributes, $user, $resource);
}