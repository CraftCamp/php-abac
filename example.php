<?php

    require_once('vendor/autoload.php');

    use PhpAbac\AbacFactory;

    $countries = include('tests/fixtures/countries.php');
    $visas = include('tests/fixtures/visas.php');
    $users = include('tests/fixtures/users.php');
    $vehicles = include('tests/fixtures/vehicles.php');
    
    $abac = AbacFactory::getAbac([__DIR__.'/tests/fixtures/policy_rules.yml']);
    
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
    if ($user1Vehicle === true) {
        echo("GRANTED : The vehicle 1 is able to be approved for the user 1\n");
    } else {
        echo("FAIL : The system didn't grant access\n");
    }
    $user3Vehicle = $abac->enforce('vehicle-homologation', $users[2], $vehicles[1], [
        'dynamic_attributes' => ['proprietaire' => 3]
    ]);
    if ($user3Vehicle !== true) {
        echo("DENIED : The vehicle 2 is not approved for the user 3 because its last technical review is too old\n");
    } else {
        echo("FAIL : The system didn't deny access\n");
    }
    $user4Vehicle = $abac->enforce('vehicle-homologation', $users[3], $vehicles[3], [
        'dynamic_attributes' => ['proprietaire' => 4]
    ]);
    if ($user4Vehicle !== true) {
        echo("DENIED : The vehicle 4 is not able to be approved for the user 4 because he has no driving license\n");
    } else {
        echo("FAIL : The system didn't deny access\n");
    }
    $user5Vehicle = $abac->enforce('vehicle-homologation', $users[3], $vehicles[3], [
        'dynamic_attributes' => ['proprietaire' => 1]
    ]);
    if ($user5Vehicle !== true) {
        echo("DENIED : The vehicle 4 is not able to be approved for the user 2 because he doesn't own the vehicle\n");
    } else {
        echo("FAIL : The system didn't deny access\n");
    }
    $userTravel1 = $abac->enforce('travel-to-foreign-country', $users[0], null, [
        'dynamic_attributes' => [
            'code-pays' => 'US'
        ]
    ]);
    if ($userTravel1 !== true) {
        echo("DENIED: The user 1 is not allowed to travel to the USA because he doesn't have an US visa\n");
    } else {
        echo('FAIL: The system didn\'t deny access');
    }
    $userTravel2 = $abac->enforce('travel-to-foreign-country', $users[1], null, [
        'dynamic_attributes' => [
            'code-pays' => 'US'
        ]
    ]);
    if ($userTravel2 === true) {
        echo("GRANTED: The user 2 is allowed to travel to the USA\n");
    } else {
        echo('FAIL: The system didn\'t grant access');
    }
