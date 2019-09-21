<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfirmSittingRequestType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('yes', SubmitType::class, [
                'label' => 'Oui',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
            ->add('no', SubmitType::class, [
                'label' => 'Non',
                'attr' => [
                    'class' => 'btn btn-outline-primary',
                ],
            ])
        ;
    }
}
