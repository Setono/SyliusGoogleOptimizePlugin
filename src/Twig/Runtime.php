<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Twig;

use Setono\SyliusGoogleOptimizePlugin\Context\ExperimentContextInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class Runtime implements RuntimeExtensionInterface
{
    private ExperimentContextInterface $experimentContext;

    public function __construct(ExperimentContextInterface $experimentContext)
    {
        $this->experimentContext = $experimentContext;
    }

    /**
     * Returns true if
     * - the user is assigned to the original/control variant
     * - the experiment does not exist
     * - the experiment ended without a winner
     *
     * @param string $experiment Either the experiment code or the Google experiment id
     */
    public function original(string $experiment): bool
    {
        if(!$this->experimentContext->hasExperiment($experiment)) {
            return true;
        }

        $experimentWithAllocatedVariant = $this->experimentContext->getExperiment($experiment);
        if($experimentWithAllocatedVariant->getVariant()->isOriginal()) {
            return true;
        }


    }

    /**
     * Returns true if the user is assigned to the given variant on the given experiment
     *
     * @param string $experiment Either the experiment code or the Google experiment id
     * @param string|integer $variant Either the variant code or the position/index
     */
    public function variant(string $experiment, $variant): bool
    {
        $experiments = $this->experimentContext->getExperiments();
    }
}
