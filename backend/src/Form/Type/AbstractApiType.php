<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;

abstract class AbstractApiType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
