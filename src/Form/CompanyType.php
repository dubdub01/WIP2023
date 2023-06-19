<?php

namespace App\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Sector;
use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Visibility', CheckboxType::class)
            ->add('Name', TextType::class)
            ->add('eMail', EmailType::class)
            ->add('cover', FileType::class, [
                'label' => "Avatar(jpg,png,gif)",
                "required" => true
            ])->add('Description')
            ->add('sector', EntityType::class, [
                'class' => Sector::class,
                'choice_label' => 'name',
                'label' => 'Secteur d\'activité',
                'required' => true,
                'multiple' => true,
                'expanded' => true,
            ]);
            $builder->get('cover')->addModelTransformer(new class implements DataTransformerInterface {
                public function transform($file): ?string
                {
                    return null;
                }
    
                public function reverseTransform($file): ?File
                {
                    if ($file instanceof UploadedFile) {
                        return $file;
                    }
    
                    if (is_string($file)) {
                        return new File($file);
                    }
    
                    return null;
                }
            });
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
