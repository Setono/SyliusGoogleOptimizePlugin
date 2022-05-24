<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\CookieManager;

use Symfony\Component\HttpFoundation\Cookie;

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

    public function createCookie(string $name, \DateTimeInterface $expires = null): Cookie
    {
        if (null === $expires) {
            $expires = (new \DateTimeImmutable())->add(new \DateInterval('P1Y'));
        }

        return Cookie::create($name, json_encode($this, \JSON_THROW_ON_ERROR), $expires);
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
