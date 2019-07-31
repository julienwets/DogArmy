<?php

namespace App\Form;

use App\Entity\Dog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo (.jpg ou .png)',
                'delete_label' => "Supprimer l'image",
                'required' => true,
                'download_uri' => false
            ])
            ->add('name', TextType::class, [])
            ->add('gender', ChoiceType::class, [
                'label' =>  "Je suis",
                'choices'  => [
                    'Un mâle' => 'Un mâle',
                    'Une femelle' => 'Une femelle',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime'
            ])
            ->add('details', ChoiceType::class, [
                'label' =>  "Je",
                'choices'  => [
                    'Suis stérilisé(e)' => 'Suis stérilisé(e)',
                    'Ne perds pas mes poils' => 'Ne perds pas mes poils',
                    'Suis adopté' => 'Suis adopté',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('instagram', TextType::class, [
                'required' => false,
            ])
            ->add('houseTrained', ChoiceType::class, [
                'label' =>  "Suis-je propre ?",
                'choices'  => [
                    'Oui - complètement' => 'Oui - complètement',
                    'Oui - avec quelques accidents' => 'Oui - avec quelques accidents',
                    'Pas encore' => 'Pas encore',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('description', TextareaType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dog::class,
        ]);
    }
}
