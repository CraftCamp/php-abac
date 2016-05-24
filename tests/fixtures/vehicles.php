<?php

use PhpAbac\Example\Vehicle;

return [
    (new Vehicle())
    ->setId(1)
    ->setOwner($users[0])
    ->setBrand('Renault')
    ->setModel('Mégane')
    ->setLastTechnicalReviewDate(new \DateTime('2014-08-19 11:03:38'))
    ->setManufactureDate(new \DateTime('2015-08-19 11:03:38'))
    ->setOrigin('FR')
    ->setEngineType('diesel')
    ->setEcoClass('C'),
    (new Vehicle())
    ->setId(2)
    ->setOwner($users[2])
    ->setBrand('Fiat')
    ->setModel('Stilo')
    ->setLastTechnicalReviewDate(new \DateTime('2008-08-19 11:03:38'))
    ->setManufactureDate(new \DateTime('2004-08-19 11:03:38'))
    ->setOrigin('IT')
    ->setEngineType('diesel')
    ->setEcoClass('C'),
    (new Vehicle())
    ->setId(3)
    ->setOwner($users[0])
    ->setBrand('Alpha Roméo')
    ->setModel('Mito')
    ->setLastTechnicalReviewDate(new \DateTime('2014-08-19 11:03:38'))
    ->setManufactureDate(new \DateTime('2013-08-19 11:03:38'))
    ->setOrigin('FR')
    ->setEngineType('gasoline')
    ->setEcoClass('D'),
    (new Vehicle())
    ->setId(4)
    ->setOwner($users[3])
    ->setBrand('Fiat')
    ->setModel('Punto')
    ->setLastTechnicalReviewDate(new \DateTime('2015-08-19 11:03:38'))
    ->setManufactureDate(new \DateTime('2010-08-19 11:03:38'))
    ->setOrigin('FR')
    ->setEngineType('diesel')
    ->setEcoClass('B'),
];