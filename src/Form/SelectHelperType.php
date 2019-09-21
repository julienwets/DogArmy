<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SelectHelperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('selectHelper', SubmitType::class, [
                'label' => 'Sélectionner',
                'attr' => [
                    'class' => 'btn-link p-0 pb-1'
                ]
            ])
        ;
    }

    
}
