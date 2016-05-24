# Attributes

Introduction
-------

There are two types of attributes : basic attributes (for users and resources), and environment attributes.

Basic attributes are stored in database. They contain data to search user and resource attributes in database.

The attributes table, related to the policy rules table, contain the location of the attributes we want to check.

They also contain information telling the library how to treat this data.

The library use several comparison types to know what to do with the given attribute.

For example, if we consider that an user's roles are attributes, we will can create a policy rule which checks if an user have the Administrator role to delete a Post.

When the rule will be checked, the library will access the user's roles property and compare it to the required Administrator role to know if this role is contained amongst them.

Create an Attribute
-----------

```php
use PhpAbac\Abac;

$abac = new Abac($pdoConnection);

$policyRuleManager = Abac::get('policy-rule-manager');
$policyRule = $policyRuleManager->getRuleByName('posts-management');
// This attribute will check that the post type is equal to 2
$policyRule->createPolicyRuleAttribute($policyRule, [
    'attribute' => [
    	'name' => 'Post Type',
        'column' => 'type.label', // Chained attributes, accessible with $post->getType()->getLabel()
    ],
    'type' => 'object', // We indicate that this is an attribute of the resource
    'comparison_type' => 'String',
    'comparison' => 'isEqual',
    'value' => 'public' // Supposing for example that the post type is an object with a label property defining its scope
]);
```