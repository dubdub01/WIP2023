<?php

namespace App\Form;

use App\Entity\Worker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class WorkerType extends AbstractType
{
    


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Firsname')
            ->add('Lastname')
            ->add('Age', DateType::class, [
                'years' => $options['years_range']
            ])
            ->add('gender')
            ->add('Description')
            ->add('Visibility')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $currentYear = (int)date('Y');
        $yearsRange = range($currentYear - 100, $currentYear);

        $resolver->setDefaults([
            'years_range' => $yearsRange,
            'data_class' => Worker::class,
        ]);
    }
}
