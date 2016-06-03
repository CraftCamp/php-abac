<?php

use PhpAbac\Example\Country;

return [
    (new Country())
    ->setName('France')
    ->setCode('FR'),
    (new Country())
    ->setName('United Kingdoms')
    ->setCode('UK'),
    (new Country())
    ->setName('United States')
    ->setCode('US'),
];
