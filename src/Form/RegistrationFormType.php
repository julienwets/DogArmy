<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'constraints' => [
                     new Length([
                        'max' => 25,
                        'maxMessage' => 'Votre nom ne peut pas comporter plus de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'min' => 2,
                        'minMessage' => 'Votre nom doit faire au moins {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new NotCompromisedPassword([
                        'message' => "Ce mot de passe a déja été compromis lors d'un vol de données sur un autre site ! Veuillez en utiliser un autre.",
                        'skipOnError' => true,
                    ])
                ],
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
                'invalid_message' => 'Les deux mots de passe ne sont pas identiques'
            ])
            ->add('zipCode', TextType::class, [
                'constraints' => [
                    new notBlank(['message' => 'Veuillez entrer un code postal valide']),
                    new Length(['min' => 4,
                                'max' => 4,
                                'exactMessage' => 'Veuillez entrer un code postal valide'
                                ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Vous devez accepter nos conditions d'utilisation",
                    ]),
                ],
            ])
            ->add('agreePrivacy', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Vous devez accepter notre politique de confidentialité",
                    ]),
                ],
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
