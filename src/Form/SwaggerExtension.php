<?php

namespace ZQuintana\LaraSwag\Form;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SwaggerExtension
 */
class SwaggerExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FormType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'swagger_type'        => null,
            'swagger_description' => null,
        ]);
        $resolver->setAllowedValues('swagger_type', [
            'integer', 'long', 'float','double', 'string',
            'byte', 'binary', 'boolean', 'date', 'dateTime',
            'password', null,
        ]);
    }
}
