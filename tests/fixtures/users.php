<?php

use PhpAbac\Example\User;

return [
    (new User())
    ->setId(1)
    ->setName('John Doe')
    ->setAge(36)
    ->setParentNationality('FR')
    ->addVisa($visas[0])
    ->addVisa($visas[1])
    ->setHasDoneJapd(false)
    ->setHasDrivingLicense(true),
    (new User())
    ->setId(2)
    ->setName('Thierry')
    ->setAge(24)
    ->addVisa($visas[2])
    ->setParentNationality('FR')
    ->setHasDoneJapd(false)
    ->setHasDrivingLicense(false),
    (new User())
    ->setId(3)
    ->setName('Jason')
    ->setAge(17)
    ->setParentNationality('FR')
    ->setHasDoneJapd(true)
    ->setHasDrivingLicense(true),
    (new User())
    ->setId(4)
    ->setName('Bouddha')
    ->setAge(556)
    ->setParentNationality('FR')
    ->setHasDoneJapd(true)
    ->setHasDrivingLicense(false),
];