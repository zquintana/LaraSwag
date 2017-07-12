<?php

namespace ZQuintana\LaraSwag\Model;

use Symfony\Component\PropertyInfo\Type;

/**
 * Class Model
 */
final class Model extends AbstractModel
{
    /**
     * @var Type
     */
    private $type;

    /**
     * @var array|null
     */
    private $groups;

    /**
     * Model constructor.
     * @param Type       $type
     * @param array|null $groups
     */
    public function __construct(Type $type, array $groups = null)
    {
        $this->type = $type;
        $this->groups = $groups;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string[]|null
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
        return $this->getTypeShortName($this->type);
    }

    /**
     * @param Type $type
     * @return string
     */
    private function getTypeShortName(Type $type): string
    {
        if (null !== $type->getCollectionValueType()) {
            return $this->getTypeShortName($type->getCollectionValueType()).'[]';
        }

        if (Type::BUILTIN_TYPE_OBJECT === $type->getBuiltinType()) {
            $parts = explode('\\', $type->getClassName());

            return end($parts);
        }

        return $type->getBuiltinType();
    }
}
