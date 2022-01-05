<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Context;

use InvalidArgumentException;
use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;
use Setono\SyliusGoogleOptimizePlugin\Repository\ExperimentRepositoryInterface;

final class VariantContext implements VariantContextInterface
{
    private ExperimentRepositoryInterface $experimentRepository;

    public function __construct(ExperimentRepositoryInterface $experimentRepository)
    {
        $this->experimentRepository = $experimentRepository;
    }

    public function getVariant(string $experiment): VariantInterface
    {
        // todo maybe it would be better to just fetch all experiments because we need them later when saving them to a cookie
        $exp = $this->experimentRepository->findOneByCodeOrGoogleExperimentId($experiment);
        if (null === $exp) {
            throw new InvalidArgumentException(sprintf('An experiment with the code or google experiment id "%s" does not exist', $experiment));
        }

        if ($exp->hasWinner()) {
            return $exp->getWinner();
        }

        if ($exp->hasEnded()) {
            return $exp->getOriginalVariant();
        }

        $randomVariantNumber = random_int(1, count($exp->getVariants()));

        $i = 1;
        foreach ($exp->getVariants() as $variant) {
            if ($i === $randomVariantNumber) {
                return $variant;
            }

            ++$i;
        }

        throw new \LogicException('The number of variants in the experiment does not match the actual variants returned. This should not be possible.');
    }
}
