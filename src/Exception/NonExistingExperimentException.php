<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Exception;

final class NonExistingExperimentException extends \InvalidArgumentException
{
    /**
     * @param int|string $experiment
     */
    public static function fromExperiment($experiment): self
    {
        return new self(sprintf('An experiment with the id, code or google experiment id "%s" does not exist', (string) $experiment));
    }
}
