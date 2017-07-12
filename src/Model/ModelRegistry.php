<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\Model;

use EXSyst\Component\Swagger\Schema;
use EXSyst\Component\Swagger\Swagger;
use ZQuintana\LaraSwag\Describer\ModelRegistryAwareInterface;
use ZQuintana\LaraSwag\ModelDescriber\ModelDescriberInterface;
use Symfony\Component\PropertyInfo\Type;

/**
 * Class ModelRegistry
 */
final class ModelRegistry
{
    /**
     * @var array
     */
    private $unregistered = [];

    /**
     * @var ModelInterface[]
     */
    private $models = [];

    /**
     * @var array
     */
    private $names = [];

    /**
     * @var array|ModelDescriberInterface[]
     */
    private $modelDescribers = [];

    /**
     * @var Swagger
     */
    private $api;


    /**
     * ModelRegistry constructor.
     *
     * @param array   $modelDescribers
     * @param Swagger $api
     */
    public function __construct(array $modelDescribers, Swagger $api)
    {
        $this->modelDescribers = $modelDescribers;
        $this->api = $api;
    }

    /**
     * @param ModelInterface $model
     * @return string
     */
    public function register(ModelInterface $model): string
    {
        $hash = $model->getHash();
        if (isset($this->names[$hash])) {
            return '#/definitions/'.$this->names[$hash];
        }

        $this->names[$hash] = $name = $model->getName();
        $this->models[$hash] = $model;
        $this->unregistered[] = $hash;

        // Reserve the name
        $this->api->getDefinitions()->get($name);

        return '#/definitions/'.$name;
    }

    /**
     * @internal
     */
    public function registerDefinitions()
    {
        while (count($this->unregistered)) {
            $tmp = [];
            foreach ($this->unregistered as $hash) {
                $tmp[$this->names[$hash]] = $this->models[$hash];
            }
            $this->unregistered = [];

            foreach ($tmp as $name => $model) {
                $schema = null;
                foreach ($this->modelDescribers as $modelDescriber) {
                    if ($modelDescriber instanceof ModelRegistryAwareInterface) {
                        $modelDescriber->setModelRegistry($this);
                    }
                    if ($modelDescriber->supports($model)) {
                        $schema = new Schema();
                        $modelDescriber->describe($model, $schema);

                        break;
                    }
                }

                if (null === $schema) {
                    throw new \LogicException(sprintf('Schema of type "%s" can\'t be generated, no describer supports it.', $this->typeToString($model->getType())));
                }

                $this->api->getDefinitions()->set($name, $schema);
            }
        }
    }

    private function typeToString(Type $type): string
    {
        if (Type::BUILTIN_TYPE_OBJECT === $type->getBuiltinType()) {
            return $type->getClassName();
        } elseif ($type->isCollection()) {
            if (null !== $type->getCollectionValueType()) {
                return $this->typeToString($type->getCollectionValueType()).'[]';
            } else {
                return 'mixed[]';
            }
        } else {
            return $type->getBuiltinType();
        }
    }
}
