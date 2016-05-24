Policy Rules
============

Introduction
------------

A policy rule is a set of several attributes.

When the appplication checks a rule, these attributes are retrieved.

Each attribute is compared with the expected value, which can be dynamic or static.

If all these comparisons return true, the rule will be enforced for the given user.

A rule can contains only user attributes, or object and environment attributes too.

Create a Policy Rule
-------------------

```php
use PhpAbac\Abac;

use PhpAbac\Model\PolicyRule;
use PhpAbac\Model\PolicyRuleAttribute;
use PhpAbac\Model\Attribute;

$abac = new Abac($pdoConnection);

$policyRule =
    (new PolicyRule())
    ->setName('medical-reports-access')
    ->addPolicyRuleAttribute(
    	(new PolicyRuleAttribute())
        ->setType('user')
        ->setComparisonType('Array')
        ->setComparison('intersect')
        ->setValue(['ROLE_MEDIC', 'ROLE_DIRECTOR'])
        ->setAttribute(
            (new Attribute())
            ->setName('User Role')
            ->setProperty('roles')
        )
    )
    ->addPolicyRuleAttribute(
    	(new PolicyRuleAttribute())
        ->setType('object')
        ->setComparisonType('Numeric')
        ->setComparison('isEqual')
        ->setValue('dynamic')
        ->setAttribute(
            (new Attribute())
            ->setName('Medical report service')
            ->setProperty('service.id')
        )
    )
;

$policyRuleManager = Abac::get('policy-rule-manager');
$policyRuleManager->create($policyRule);
```