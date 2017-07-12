<?php

namespace ZQuintana\LaraSwag\Annotation;

use Swagger\Annotations\Parameter;

/**
 * Class Form
 *
 * @Annotation
 */
final class Form extends Parameter
{
    /**
     * @var string
     */
    public $class;

    /**
     * @var string[]
     */
    public $groups;

    /**
     * @var string
     */
    public $in = 'body';


    /**
     * @param array $parents
     * @param array $skip
     *
     * @return bool
     */
    public function validate($parents = [], $skip = [])
    {
        if (empty($this->name)) {
            $this->name = $this->class;
        }

        return parent::validate($parents, $skip);
    }
}
