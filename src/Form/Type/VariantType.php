<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

final class VariantType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('position', IntegerType::class, [
                'label' => 'setono_sylius_google_optimize.form.variant.position',
            ])
            ->addEventSubscriber(new AddCodeFormSubscriber(null, [
                'disabled' => false,
            ]))
        ;
    }
}
