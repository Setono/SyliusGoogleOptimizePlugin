<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\CookieManager;

/**
 * @implements \IteratorAggregate<Experiment>
 */
final class Experiments implements \Countable, \IteratorAggregate, \JsonSerializable
{
    /** @var list<Experiment> */
    public array $experiments = [];

    /**
     * @param list<Experiment> $experiments
     */
    public function __construct(array $experiments = [])
    {
        foreach ($experiments as $experiment) {
            $this->add($experiment);
        }
    }

    public function add(Experiment $experiment): void
    {
        $this->experiments[] = $experiment;
    }

    public function count(): int
    {
        return count($this->experiments);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->experiments);
    }

    public function isEmpty(): bool
    {
        return [] === $this->experiments;
    }

    public function jsonSerialize(): array
    {
        $experiments = [];
        foreach ($this->experiments as $experiment) {
            $experiments[$experiment->experiment] = $experiment->variant;
        }

        return $experiments;
    }
}
