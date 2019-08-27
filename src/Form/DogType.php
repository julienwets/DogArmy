<?php

namespace App\Form;

use App\Entity\Dog;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DogType extends AbstractType
{
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', FileType::class, [
                'label' => 'Photo (.jpg ou .png)',
                'required' => false,
            ])
            ->add('name', TextType::class, [])
            ->add('gender', ChoiceType::class, [
                'label' =>  "Je suis",
                'label_attr' => ['class' => 'custom-control-label'],
                'choices'  => [
                    'Un mâle' => 'Un mâle',
                    'Une femelle' => 'Une femelle',
                ],
                'choice_attr' => function () {
                    return ['class' => 'custom-control-input'];
                },
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
            ])
            ->add('details', ChoiceType::class, [
                'label' =>  "Détails",
                'label_attr' => ['class' => 'custom-control-label'],
                'choices'  => [
                    'Je suis stérilisé(e)' => 'Je suis stérilisé(e)',
                    'Je ne perds pas mes poils' => 'Je ne perds pas mes poils',
                    'Je suis adopté' => 'Je suis adopté',
                ],
                'choice_attr' => function () {
                    return ['class' => 'custom-control-input'];
                },
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('instagram', TextType::class, [
                'required' => false,
            ])
            ->add('houseTrained', ChoiceType::class, [
                'label' =>  "Ai-je appris à être propre ?",
                'label_attr' => ['class' => 'custom-control-label'],
                'choices'  => [
                    'Oui - complètement' => 'Oui - complètement',
                    'Oui - avec quelques accidents' => 'Oui - avec quelques accidents',
                    'Pas encore' => 'Pas encore',
                ],
                'choice_attr' => function () {
                    return ['class' => 'custom-control-input'];
                },
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'data-toggle' => 'autosize',
                    'rows' => 5
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dog::class,
        ]);
    }
}
