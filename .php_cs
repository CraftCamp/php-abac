<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude(array('data', 'doc', 'sql', 'vendor'))
    ->in(__DIR__)
;
return PhpCsFixer\Config::create()
    ->setRules(array('@PSR2' => true))
    ->setFinder($finder)
;