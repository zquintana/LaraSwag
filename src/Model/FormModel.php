<?php

namespace ZQuintana\LaraSwag\Model;

/**
 * Class Form
 */
final class FormModel extends AbstractModel
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var array|null
     */
    private $groups;


    /**
     * Form constructor.
     *
     * @param string $class
     * @param string $name
     * @param array  $groups
     */
    public function __construct(string $class, string $name = null, array $groups = null)
    {
        $this->class  = $class;
        $this->name   = $name ?: $class;
        $this->groups = $groups;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return array|null
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
