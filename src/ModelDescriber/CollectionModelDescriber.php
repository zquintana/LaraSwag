<?php

namespace ZQuintana\LaraSwag\ModelDescriber;

use EXSyst\Component\Swagger\Schema;
use ZQuintana\LaraSwag\Describer\ModelRegistryAwareInterface;
use ZQuintana\LaraSwag\Describer\ModelRegistryAwareTrait;
use ZQuintana\LaraSwag\Model\Model;
use ZQuintana\LaraSwag\Model\ModelInterface;

/**
 * Class CollectionModelDescriber
 */
class CollectionModelDescriber implements ModelDescriberInterface, ModelRegistryAwareInterface
{
    use ModelRegistryAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function describe(ModelInterface $model, Schema $schema)
    {
        /** @var Model $model */
        $schema->setType('array');
        $schema->getItems()->setRef(
            $this->modelRegistry->register(new Model($model->getType()->getCollectionValueType(), $model->getGroups()))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ModelInterface $model): bool
    {
        return $model instanceof Model && $model->getType()->isCollection() && null !== $model->getType()->getCollectionValueType();
    }
}
