<?php

use PhpAbac\Example\Vehicle;

return [
    (new Vehicle())
    ->setId(1)
    ->setOwner($users[0])
    ->setBrand('Renault')
    ->setModel('Mégane')
    ->setLastTechnicalReviewDate(new \DateTime('-1 year'))
    ->setManufactureDate(new \DateTime('-3 years'))
    ->setOrigin('FR')
    ->setEngineType('diesel')
    ->setEcoClass('C'),
    (new Vehicle())
    ->setId(2)
    ->setOwner($users[2])
    ->setBrand('Fiat')
    ->setModel('Stilo')
    ->setLastTechnicalReviewDate(new \DateTime('-7 years'))
    ->setManufactureDate(new \DateTime('-14 years'))
    ->setOrigin('IT')
    ->setEngineType('diesel')
    ->setEcoClass('C'),
    (new Vehicle())
    ->setId(3)
    ->setOwner($users[0])
    ->setBrand('Alpha Roméo')
    ->setModel('Mito')
    ->setLastTechnicalReviewDate(new \DateTime('-2 years'))
    ->setManufactureDate(new \DateTime('-4 years'))
    ->setOrigin('FR')
    ->setEngineType('gasoline')
    ->setEcoClass('D'),
    (new Vehicle())
    ->setId(4)
    ->setOwner($users[3])
    ->setBrand('Fiat')
    ->setModel('Punto')
    ->setLastTechnicalReviewDate(new \DateTime('-1 year'))
    ->setManufactureDate(new \DateTime('-6 years'))
    ->setOrigin('FR')
    ->setEngineType('diesel')
    ->setEcoClass('B'),
];
