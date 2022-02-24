<?php

namespace App\Form;

use App\Entity\SubStep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('createdAt')
//            ->add('updatedAt')
//            ->add('deletedAt')
//            ->add('identifier')
            ->add('name' , TextType::class , [
                'label' => 'Sub Step Name'
            ])
            ->add('column3' , null , [
                'label' => 'Column 3'
            ])
//            ->add('products')
//            ->add('inspection')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SubStep::class,
        ]);
    }
}
