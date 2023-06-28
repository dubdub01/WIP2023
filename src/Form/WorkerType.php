<?php

namespace App\Form;

use App\Entity\Skills;
use App\Entity\Worker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class WorkerType extends AbstractType
{
    


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Visibility', CheckboxType::class, [
            'label' => 'Visibilité',
            'required' => false,
            ])      
            ->add('Firsname', TextType::class,[
                'label' => 'Prénom'
            ])
            ->add('Lastname', TextType::class,[
                'label' => 'Nom de famille',
            ])
            ->add('Age', DateType::class, [                
                'label' => 'Date de naissance',
                'years' => $options['years_range']
            ])
            ->add('gender', TextType::class,[
                'label' => 'Genre',
            ])
            ->add('Description', TextType::class)
            ->add('Skills', EntityType::class, [
                'class' => Skills::class,
                'choice_label' => 'name',
                'label' => 'Compétences',
                'required' => true,
                'multiple' => true,
                'expanded' => true,
            ])
                  
            ->add('cv', FileType::class, [
                'label' => 'CV (PDF file)',
                'mapped' =>false,
                'required' =>false,
                'constraints' =>[
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF  document',
                    ])
                ],
                'mapped' =>false,
                
            ])
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
