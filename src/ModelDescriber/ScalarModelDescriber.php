<?php

namespace ZQuintana\LaraSwag\ModelDescriber;

use EXSyst\Component\Swagger\Schema;
use ZQuintana\LaraSwag\Model\Model;
use Symfony\Component\PropertyInfo\Type;
use ZQuintana\LaraSwag\Model\ModelInterface;

/**
 * Class ScalarModelDescriber
 */
class ScalarModelDescriber implements ModelDescriberInterface
{
    /**
     * @var array
     */
    private static $supportedTypes = [
        Type::BUILTIN_TYPE_INT    => 'integer',
        Type::BUILTIN_TYPE_FLOAT  => 'float',
        Type::BUILTIN_TYPE_STRING => 'string',
        Type::BUILTIN_TYPE_BOOL   => 'boolean',
    ];


    /**
     * {@inheritdoc}
     */
    public function describe(ModelInterface $model, Schema $schema)
    {
        /** @var Model $model */
        $type = self::$supportedTypes[$model->getType()->getBuiltinType()];
        $schema->setType($type);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ModelInterface $model): bool
    {
        return $model instanceof Model && isset(self::$supportedTypes[$model->getType()->getBuiltinType()]);
    }
}
