<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Factory;

use Setono\SyliusGoogleOptimizePlugin\Model\VariantInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

final class VariantFactory implements VariantFactoryInterface
{
    private FactoryInterface $decorated;

    public function __construct(FactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function createNew(): VariantInterface
    {
        /** @var VariantInterface|object $obj */
        $obj = $this->decorated->createNew();
        Assert::isInstanceOf($obj, VariantInterface::class);

        return $obj;
    }

    public function createWithData(string $code, int $position): VariantInterface
    {
        $obj = $this->createNew();
        $obj->setCode($code);
        $obj->setPosition($position);

        return $obj;
    }

    public function createOriginal(): VariantInterface
    {
        $obj = $this->createNew();
        $obj->setCode(VariantInterface::CODE_ORIGINAL);
        $obj->setPosition(VariantInterface::POSITION_ORIGINAL);

        return $obj;
    }
}
