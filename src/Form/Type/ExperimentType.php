<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ExperimentType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('googleExperimentId', TextType::class, [
                'label' => 'setono_sylius_google_optimize.form.experiment.google_experiment_id',
                'attr' => [
                    'placeholder' => 'setono_sylius_google_optimize.form.experiment.google_experiment_id_placeholder',
                ],
            ])
            ->add('variants', VariantCollectionType::class, [
                'label' => 'setono_sylius_google_optimize.form.experiment.variants',
            ])
            ->addEventSubscriber(new AddCodeFormSubscriber(null, [
                'attr' => [
                    'placeholder' => 'setono_sylius_google_optimize.form.experiment.code_placeholder',
                ],
            ]))
        ;
    }
}
