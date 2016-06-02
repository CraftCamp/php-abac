<?php

namespace PhpAbac\Comparison;

use PhpAbac\Manager\ComparisonManager;

abstract class AbstractComparison {
    /** @var \PhpAbac\Manager\ComparisonManager **/
    protected $comparisonManager;
    
    /**
     * @param \PhpAbac\Manager\ComparisonManager $comparisonManager
     */
    public function __construct(ComparisonManager $comparisonManager) {
        $this->comparisonManager = $comparisonManager;
    }
}