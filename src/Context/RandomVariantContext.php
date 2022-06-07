<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Context;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;
use Setono\SyliusGoogleOptimizePlugin\Provider\ExperimentProviderInterface;

final class RandomVariantContext implements VariantContextInterface
{
    private ExperimentProviderInterface $experimentProvider;

    public function __construct(ExperimentProviderInterface $experimentProvider)
    {
        $this->experimentProvider = $experimentProvider;
    }

    public function getVariant(string $experiment): VariantInterface
    {
        // todo an option could also be to return an instance of VariantInterface with the values we know that an original variant should have, i.e. position = 0 and code = original
        $obj = $this->experimentProvider->getExperiment($experiment);

        return self::getVariantFromExperiment($obj);
    }

    private static function getVariantFromExperiment(ExperimentInterface $experiment): VariantInterface
    {
        $winner = $experiment->getWinner();
        if (null !== $winner) {
            return $winner;
        }

        if ($experiment->hasEnded()) {
            return $experiment->getOriginalVariant();
        }

        $randomVariantNumber = random_int(1, count($experiment->getVariants()));

        $i = 1;
        foreach ($experiment->getVariants() as $variant) {
            if ($i === $randomVariantNumber) {
                return $variant;
            }

            ++$i;
        }

        throw new \LogicException('The number of variants in the experiment does not match the actual variants returned by $experiment->getVariants(). This should not be possible.');
    }
}
