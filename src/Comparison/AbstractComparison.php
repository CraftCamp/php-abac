<?php

namespace PhpAbac\Comparison;

use PhpAbac\Manager\ComparisonManager;

abstract class AbstractComparison
{
    /** @var ComparisonManager **/
    protected $comparisonManager;
    
    /**
     * @param ComparisonManager $comparisonManager
     */
    public function __construct(ComparisonManager $comparisonManager)
    {
        $this->comparisonManager = $comparisonManager;
    }
}
