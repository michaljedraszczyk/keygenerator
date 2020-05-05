<?php

namespace App\Form\Type;

use App\Form\Model\DateKeyDatesModel;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateKeyByDatesType extends AbstractApiType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', DateType::class, ['widget' => 'single_text'])
            ->add('to', DateType::class, ['widget' => 'single_text'])
            ->add('keyTemplate', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'      => DateKeyDatesModel::class,
                'csrf_protection' => false,
            ]
        );
    }
}
