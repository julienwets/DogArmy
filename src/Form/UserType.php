<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo (.jpg ou .png)',
                'delete_label' => "Supprimer l'image",
                'required' => false,
                'download_link' => false
            ])

            ->add('homeType', ChoiceType::class, [
                'label' =>  "J'habite dans",
                'choices'  => [
                     'Une maison' => 'Une maison',
                     'Un appartement' => 'Un appartement',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('availableOn', ChoiceType::class, [
                'label' => 'Je suis disponible:',
                'choices'  => [
                     'La semaine - en journée' => 'La semaine - en journée',
                     'La semaine - en soirée' => 'La semaine - en soirée',
                     'Le week-end' => 'Le week-end',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('dogSize', ChoiceType::class, [
                'label' => 'Je préfère les chiens de cette taille:',
                'choices'  => [
                     'Micro (Moins de 5kg)' => 'Micro (Moins de 5kg)',
                     'Petit (5 - 12 kg)' => 'Petit (5 - 12 kg)',
                     'Moyen (12 - 20 kg)' => 'Moyen (12 - 20 kg)',
                     'Grand (Plus de 20kg)' => 'Grand (Plus de 20kg)',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('homeDetails', ChoiceType::class, [
                'label' => 'Dans ma maison, il y a',
                'choices'  => [
                     'Des enfants (0 - 12 ans)' => 'Des enfants (0 - 12 ans)',
                     'Un jardin' => 'Un jardin',
                     'Un ou plusieurs chats' => 'Un ou plusieurs chats',
                     "D'autres animaux" => "D'autres animaux",
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('duringWork', ChoiceType::class, [
                'label' => 'Les jours où je travaille, mon/mes chien(s)',
                'choices'  => [
                     'Restent seuls à la maison' => 'Restent seuls à la maison',
                     "Restent à la maison avec quelqu'un" => "Restent à la maison avec quelqu'un",
                     'Vont à une garderie' => 'Vont à une garderie',
                     'Vont avec moi au bureau' => 'Vont avec moi au bureau',
                     'Font une promenade pendant la journée' => 'Font une promenade pendant la journée',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('otherPreferences', ChoiceType::class, [
                'label' => 'Autres préférences',
                'choices'  => [
                     'Chiens stérilisés uniquement' => 'Chiens stérilisés uniquement',
                     'Chiens qui ne perdent pas leurs poils uniquement' => 'Chiens qui ne perdent ',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
