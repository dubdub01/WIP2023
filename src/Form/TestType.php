<?php

namespace App\Form;

use App\Entity\Worker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Firsname')
            ->add('Lastname')
            ->add('Age')
            ->add('gender')
            ->add('Description')
            ->add('Visibility')
            ->add('Slug')
            ->add('cv')
            ->add('skills')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Worker::class,
        ]);
    }
}
