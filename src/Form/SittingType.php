<?php

namespace App\Form;

use App\Entity\Dog;
use App\Entity\Sitting;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SittingType extends AbstractType
{
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startTime', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime'
            ])
            ->add('endTime', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime'
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['cols' => '5',
                            'rows' => '5',
                            'minlength' => '20',
                        ],
            ])
            ->add('dogs', EntityType::class, [
            'class' => Dog::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
            'label_attr' => ['class' => 'custom-control-label'],
            'choice_attr' => function () {
                return ['class' => 'custom-control-input'];
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('d')
                    ->where('d.user = :user')
                    ->setParameter('user', $this->user)
                    ->orderBy('d.name', 'ASC');
            },
            'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sitting::class,
        ]);
    }
}
