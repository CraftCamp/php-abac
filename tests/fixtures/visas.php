<?php

use PhpAbac\Example\Visa;

return [
    (new Visa())
    ->setId(1)
    ->setCountry('FR')
    ->setLastRenewal(new \DateTime())
    ->setCreatedAt(new \DateTime()),
    (new Visa())
    ->setId(2)
    ->setCountry('UK')
    ->setLastRenewal(new \DateTime())
    ->setCreatedAt(new \DateTime()),
    (new Visa())
    ->setId(3)
    ->setCountry('US')
    ->setLastRenewal(new \DateTime())
    ->setCreatedAt(new \DateTime()),
];