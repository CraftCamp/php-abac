<?php

    require_once('vendor/autoload.php');

    use PhpAbac\Abac;
    use PhpAbac\Example\User;
    use PhpAbac\Example\Vehicle;
    
    $users = [
        (new User())
        ->setId(1)
        ->setName('John Doe')
        ->setAge(36)
        ->setParentNationality('FR')
        ->setHasDoneJapd(false)
        ->setHasDrivingLicense(true),
        (new User())
        ->setId(2)
        ->setName('Thierry')
        ->setAge(24)
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
    $vehicles = [
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
        ->setOwner($users[0])
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
    
    $abac = new Abac(new \PDO('sqlite::memory:', null, null, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]));
    Abac::get('pdo-connection')->exec(file_get_contents("tests/fixtures/policy_rules.sql"));
    
    putenv('SERVICE_STATE=OPEN');
    
    $user1Nationality = $abac->enforce('nationality-access', $users[3], null, [
        'cache_result' => true,
        'cache_lifetime' => 100,
        'cache_driver' => 'memory'
    ]);
    
    if ($user1Nationality === true) {
        echo("GRANTED : The user 1 is able to be nationalized\n");
    } else {
        echo("FAIL : The system didn't grant access\n");
    }
    
    $user2Nationality = $abac->enforce('nationality-access', $users[0]);
    if ($user2Nationality !== true) {
        echo("DENIED : The user 2 is not able to be nationalized because he hasn't done his JAPD\n");
    } else {
        echo("FAIL : The system didn't deny access\n");
    }
    
    $user1Vehicle = $abac->enforce('vehicle-homologation', $users[0], $vehicles[0], [
        'dynamic_attributes' => ['proprietaire' => 1]
    ]);
    if($user1Vehicle === true) {
        echo("GRANTED : The vehicle 1 is able to be approved for the user 1\n");
    } else {
        echo("FAIL : The system didn't grant access\n");
    }
    $user3Vehicle = $abac->enforce('vehicle-homologation', $users[2], $vehicles[1], [
        'dynamic_attributes' => ['proprietaire' => 3]
    ]);
    if(!$user3Vehicle !== true) {
        echo("DENIED : The vehicle 2 is not approved for the user 3 because its last technical review is too old\n");
    } else {
        echo("FAIL : The system didn't deny access\n");
    }
    $user4Vehicle = $abac->enforce('vehicle-homologation', $users[3], $vehicles[3], [
        'dynamic_attributes' => ['proprietaire' => 4]
    ]);
    if($user4Vehicle !== true) {
        echo("DENIED : The vehicle 4 is not able to be approved for the user 4 because he has no driving license\n");
    } else {
        echo("FAIL : The system didn't deny access\n");
    }
    $user5Vehicle = $abac->enforce('vehicle-homologation', $users[3], $vehicles[3], [
        'dynamic_attributes' => ['proprietaire' => 1]
    ]);
    if($user5Vehicle !== true) {
        echo("DENIED : The vehicle 4 is not able to be approved for the user 2 because he doesn't own the vehicle\n");
    } else {
        echo("FAIL : The system didn't deny access\n");
    }
