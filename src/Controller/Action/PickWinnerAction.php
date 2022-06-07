<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Controller\Action;

use Doctrine\Persistence\ManagerRegistry;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Setono\SyliusGoogleOptimizePlugin\Model\ExperimentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PickWinnerAction
{
    use ORMManagerTrait;

    private RepositoryInterface $experimentRepository;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        RepositoryInterface $experimentRepository,
        ManagerRegistry $managerRegistry,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->experimentRepository = $experimentRepository;
        $this->managerRegistry = $managerRegistry;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(Request $request, int $experimentId, int $variantId): Response
    {
        $experiment = $this->experimentRepository->find($experimentId);
        if (!$experiment instanceof ExperimentInterface) {
            throw new NotFoundHttpException(sprintf('The experiment with id %d does not exist', $experimentId));
        }

        foreach ($experiment->getVariants() as $variant) {
            if ($variant->getId() === $variantId) {
                $experiment->setWinner($variant);

                $manager = $this->getManager($experiment);
                $manager->flush();

                return new RedirectResponse(
                    $request->headers->get('referer') ?? $this->urlGenerator->generate('setono_sylius_google_optimize_admin_experiment_index')
                );
            }
        }

        throw new NotFoundHttpException(sprintf(
            'The experiment with id %d does not have a variant with id %d not exist',
            $experimentId,
            $variantId
        ));
    }
}
