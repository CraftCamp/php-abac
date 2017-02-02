<?php

use PhpAbac\Example\Visa;

return [
    (new Visa())
    ->setId(1)
    ->setCountry($countries[0])
    ->setLastRenewal(new \DateTime())
    ->setCreatedAt(new \DateTime()),
    (new Visa())
    ->setId(2)
    ->setCountry($countries[1])
    ->setLastRenewal(new \DateTime())
    ->setCreatedAt(new \DateTime()),
    (new Visa())
    ->setId(3)
    ->setCountry($countries[2])
    ->setLastRenewal(new \DateTime())
    ->setCreatedAt(new \DateTime()),
];
