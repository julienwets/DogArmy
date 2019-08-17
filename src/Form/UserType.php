<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', FileType::class, [
                'label' => 'Photo de profil',
                'label_attr' => ['class' => 'form-control-label'],
                'required' => false
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Code postal',
                'label_attr' => ['class' => 'form-control-label'],
                'attr' => ['class' => 'form-control', 'placeholder' => '1030'],
                'constraints' => [
                    new notBlank(['message' => 'Veuillez entrer un code postal valide']),
                    new Length([
                        'min' => 4,
                        'max' => 4,
                        'exactMessage' => 'Veuillez entrer un code postal valide'
                    ])
                ]
            ])
            ->add('homeType', ChoiceType::class, [
                'label' =>  "J'habite dans",
                'label_attr' => ['class' => 'custom-control-label'],
                'choices'  => [
                    'Une maison' => 'Une maison',
                    'Un appartement' => 'Un appartement',
                ],
                'choice_attr' => function () {
                    return ['class' => 'custom-control-input'];
                },
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('availableOn', ChoiceType::class, [
                'label' => 'Je suis disponible:',
                'label_attr' => ['class' => 'custom-control-label'],
                'choices'  => [
                    'La semaine - en journée' => 'La semaine - en journée',
                    'La semaine - en soirée' => 'La semaine - en soirée',
                    'Le week-end' => 'Le week-end',
                ],
                'choice_attr' => function () {
                    return ['class' => 'custom-control-input'];
                },
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('dogSize', ChoiceType::class, [
                'label' => 'Je préfère les chiens de cette taille:',
                'label_attr' => ['class' => 'custom-control-label'],
                'choices'  => [
                    'Micro (Moins de 5kg)' => 'Micro (Moins de 5kg)',
                    'Petit (5 - 12 kg)' => 'Petit (5 - 12 kg)',
                    'Moyen (12 - 20 kg)' => 'Moyen (12 - 20 kg)',
                    'Grand (Plus de 20kg)' => 'Grand (Plus de 20kg)',
                ],
                'choice_attr' => function () {
                    return ['class' => 'custom-control-input'];
                },
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('homeDetails', ChoiceType::class, [
                'label' => 'Dans ma maison, il y a',
                'label_attr' => ['class' => 'custom-control-label'],
                'choices'  => [
                    'Des enfants (0 - 12 ans)' => 'Des enfants (0 - 12 ans)',
                    'Un jardin' => 'Un jardin',
                    'Un ou plusieurs chats' => 'Un ou plusieurs chats',
                    "D'autres animaux" => "D'autres animaux",
                ],
                'choice_attr' => function () {
                    return ['class' => 'custom-control-input'];
                },
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('duringWork', ChoiceType::class, [
                'label' => 'Les jours où je travaille, mon/mes chien(s)',
                'label_attr' => ['class' => 'custom-control-label'],
                'choices'  => [
                    'Restent seuls à la maison' => 'Restent seuls à la maison',
                    "Restent à la maison avec quelqu'un" => "Restent à la maison avec quelqu'un",
                    'Vont à une garderie' => 'Vont à une garderie',
                    'Vont avec moi au bureau' => 'Vont avec moi au bureau',
                    'Font une promenade pendant la journée' => 'Font une promenade pendant la journée',
                ],
                'choice_attr' => function () {
                    return ['class' => 'custom-control-input'];
                },
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('otherPreferences', ChoiceType::class, [
                'label' => 'Autres préférences',
                'label_attr' => ['class' => 'custom-control-label'],
                'choices'  => [
                    'Chiens stérilisés uniquement' => 'Chiens stérilisés uniquement',
                    'Chiens qui ne perdent pas leurs poils uniquement' => 'Chiens qui ne perdent ',
                ],
                'choice_attr' => function () {
                    return ['class' => 'custom-control-input'];
                },
                'expanded' => true,
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
