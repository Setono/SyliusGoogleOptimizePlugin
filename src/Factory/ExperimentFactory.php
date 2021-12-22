<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Factory;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

final class ExperimentFactory implements ExperimentFactoryInterface
{
    private FactoryInterface $decorated;

    private VariantFactoryInterface $variantFactory;

    public function __construct(FactoryInterface $decorated, VariantFactoryInterface $variantFactory)
    {
        $this->decorated = $decorated;
        $this->variantFactory = $variantFactory;
    }

    public function createNew(): ExperimentInterface
    {
        /** @var ExperimentInterface|object $obj */
        $obj = $this->decorated->createNew();
        Assert::isInstanceOf($obj, ExperimentInterface::class);

        $obj->addVariant($this->variantFactory->createWithData('original', 0));

        return $obj;
    }
}
