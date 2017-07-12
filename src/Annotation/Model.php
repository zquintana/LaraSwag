<?php

namespace ZQuintana\LaraSwag\Annotation;

use Swagger\Annotations\AbstractAnnotation;

/**
 * @Annotation
 */
final class Model extends AbstractAnnotation
{
    /** {@inheritdoc} */
    public static $_types = [
        'type' => 'string',
        'groups' => '[string]',
    ];
    public static $_required = ['type'];
    public static $_parents = [
        'Swagger\Annotations\Parameter',
        'Swagger\Annotations\Response',
    ];

    /**
     * @var string
     */
    public $type;

    /**
     * @var string[]
     */
    public $groups;
}
