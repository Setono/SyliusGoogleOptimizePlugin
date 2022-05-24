<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Provider;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Setono\SyliusGoogleOptimizePlugin\Repository\ExperimentRepositoryInterface;

final class ExperimentProvider implements ExperimentProviderInterface
{
    /** @var array<int|string, ExperimentInterface>|null */
    private ?array $experiments = null;

    private ExperimentRepositoryInterface $experimentRepository;

    public function __construct(ExperimentRepositoryInterface $experimentRepository)
    {
        $this->experimentRepository = $experimentRepository;
    }

    /**
     * @psalm-assert !null $this->experiments
     * @psalm-assert-if-true ExperimentInterface $this->experiments[$experiment]
     */
    public function hasExperiment($experiment): bool
    {
        $this->populate();

        return isset($this->experiments[$experiment]);
    }

    public function getExperiment($experiment): ExperimentInterface
    {
        if (!$this->hasExperiment($experiment)) {
            throw new \InvalidArgumentException(sprintf('The experiment "%s" does not exist', $experiment));
        }

        return $this->experiments[$experiment];
    }

    /**
     * @psalm-assert !null $this->experiments
     */
    private function populate(): void
    {
        if (null !== $this->experiments) {
            return;
        }

        $this->experiments = [];

        foreach ($this->experimentRepository->findAll() as $experiment) {
            $this->experiments[(int) $experiment->getId()] = $experiment;
            $this->experiments[(string) $experiment->getCode()] = $experiment;
            $this->experiments[(string) $experiment->getGoogleExperimentId()] = $experiment;
        }
    }
}
