<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class VariantCollectionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entry_type' => VariantType::class,
            'button_add_label' => 'setono_sylius_google_optimize.form.variant_collection.add_variant',
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'delete_empty' => true,
        ]);
    }

    public function getParent(): string
    {
        return CollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'setono_sylius_google_optimize_variant_collection';
    }
}
