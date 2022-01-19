<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Exception;

final class NonExistingVariantException extends \InvalidArgumentException
{
    /**
     * @param int|string $experiment
     * @param int|string $variant
     */
    public static function fromVariant($experiment, $variant): self
    {
        return new self(sprintf(
            'The experiment "%s" does not have a variant "%s". This should only be able to happen if you renamed the code of the variant AFTER you started the experiment which is not advisable.',
            (string) $experiment,
            (string) $variant
        ));
    }
}
