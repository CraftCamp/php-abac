<?php

    require_once('vendor/autoload.php');

    use PhpAbac\Abac;

    $abac = new Abac(new \PDO(
        'mysql:host=localhost;dbname=php_abac_test',
        'root',
        'vagrant',
        [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]
    ));
    Abac::get('pdo-connection')->exec(file_get_contents("src/php-abac/Test/fixtures/policy_rules.sql"));
    
    putenv('SERVICE_STATE=OPEN');
    
    $user1Nationality = $abac->enforce('nationality-access', 1);
    
    if($user1Nationality) {
        echo("GRANTED : The user 1 is able to be nationalized\n");
    } else {
        echo("FAIL : The system didn't grant access\n");
    }
    
    $user2Nationality = $abac->enforce('nationality-access', 2);
    if(!$user2Nationality) {
        echo("DENIED : The user 2 is not able to be nationalized because he hasn't done his JAPD\n");
    } else {
        echo("FAIL : The system didn't deny access\n");
    }

    $user1Vehicle = $abac->enforce('vehicle-homologation', 1, 1, ['proprietaire' => 1]);
    if($user1Vehicle) {
        echo("GRANTED : The vehicle 1 is able to be approved for the user 1\n");
    } else {
        echo("FAIL : The system didn't grant access\n");
    }
    $user3Vehicle = $abac->enforce('vehicle-homologation', 3, 2, ['proprietaire' => 3]);
    if(!$user3Vehicle) {
        echo("DENIED : The vehicle 2 is not approved for the user 3 because its last technical review is too old\n");
    } else {
        echo("FAIL : The system didn't deny access\n");
    }
    $user4Vehicle = $abac->enforce('vehicle-homologation', 4, 4, ['proprietaire' => 4]);
    if(!$user4Vehicle) {
        echo("DENIED : The vehicle 4 is not able to be approved for the user 4 because he has no driving license\n");
    } else {
        echo("FAIL : The system didn't deny access\n");
    }
    $user5Vehicle = $abac->enforce('vehicle-homologation', 4, 2, ['proprietaire' => 4]);
    if(!$user5Vehicle) {
        echo("DENIED : The vehicle 4 is not able to be approved for the user 2 because he doesn't own the vehicle\n");
    } else {
        echo("FAIL : The system didn't deny access\n");
    }