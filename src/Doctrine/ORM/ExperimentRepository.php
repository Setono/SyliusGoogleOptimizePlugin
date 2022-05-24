<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Doctrine\ORM;

use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Setono\SyliusGoogleOptimizePlugin\Repository\ExperimentRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

class ExperimentRepository extends EntityRepository implements ExperimentRepositoryInterface
{
    // todo this query is really a good candidate for caching (at least for a small amount of time)
    public function findAll(bool $fetchJoinVariants = true): array
    {
        $qb = $this->createQueryBuilder('o');
        if ($fetchJoinVariants) {
            $qb->select('o, v')->join('o.variants', 'v');
        }

        $res = $qb->getQuery()->getResult();

        Assert::isList($res);
        Assert::allIsInstanceOf($res, ExperimentInterface::class);

        return $res;
    }

    public function findRunning(): array
    {
        $res = $this->createQueryBuilder('o')
            ->andWhere('o.endedAt is null')
            ->getQuery()->getResult()
        ;

        Assert::isList($res);
        Assert::allIsInstanceOf($res, ExperimentInterface::class);

        return $res;
    }

    public function findOneByCode(string $code): ?ExperimentInterface
    {
        $obj = $this->createQueryBuilder('o')
            ->andWhere('o.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        Assert::nullOrIsInstanceOf($obj, ExperimentInterface::class);

        return $obj;
    }

    public function findOneByCodeOrGoogleExperimentId(string $experiment): ?ExperimentInterface
    {
        $obj = $this->createQueryBuilder('o')
            ->orWhere('o.code = :code')
            ->orWhere('o.googleExperimentId = :googleExperimentId')
            ->setParameter('code', $experiment)
            ->setParameter('googleExperimentId', $experiment)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        Assert::nullOrIsInstanceOf($obj, ExperimentInterface::class);

        return $obj;
    }
}
