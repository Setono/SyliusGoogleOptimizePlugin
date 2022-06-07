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
    public function findRunning(bool $fetchJoinVariants = true): array
    {
        $qb = $this->createQueryBuilder('o');

        if ($fetchJoinVariants) {
            $qb->select('o, v')
                ->join('o.variants', 'v')
            ;
        }

        $res = $qb->andWhere('o.endedAt is null')->getQuery()->getResult();

        Assert::isList($res);
        Assert::allIsInstanceOf($res, ExperimentInterface::class);

        return $res;
    }
}
