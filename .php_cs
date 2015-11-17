<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude(array('doc', 'sql', 'vendor'))
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->finder($finder)
;
