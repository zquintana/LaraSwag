<?php

namespace ZQuintana\LaraSwag\ModelDescriber;

use EXSyst\Component\Swagger\Schema;
use ZQuintana\LaraSwag\Describer\ModelRegistryAwareInterface;
use ZQuintana\LaraSwag\Describer\ModelRegistryAwareTrait;
use ZQuintana\LaraSwag\Model\Model;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Type;
use ZQuintana\LaraSwag\Model\ModelInterface;

/**
 * Class ObjectModelDescriber
 */
class ObjectModelDescriber implements ModelDescriberInterface, ModelRegistryAwareInterface
{
    use ModelRegistryAwareTrait;

    private $propertyInfo;


    /**
     * ObjectModelDescriber constructor.
     *
     * @param PropertyInfoExtractorInterface $propertyInfo
     */
    public function __construct(PropertyInfoExtractorInterface $propertyInfo)
    {
        $this->propertyInfo = $propertyInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function describe(ModelInterface $model, Schema $schema)
    {
        /** @var Model $model */
        $schema->setType('object');
        $properties = $schema->getProperties();

        $class = $model->getType()->getClassName();
        $context = [];
        if (null !== $model->getGroups()) {
            $context = ['serializer_groups' => $model->getGroups()];
        }

        $propertyInfoProperties = $this->propertyInfo->getProperties($class, $context);
        if (null === $propertyInfoProperties) {
            return;
        }

        foreach ($propertyInfoProperties as $propertyName) {
            $types = $this->propertyInfo->getTypes($class, $propertyName);
            if (0 === count($types)) {
                throw new \LogicException(sprintf('The PropertyInfo component was not able to guess the type of %s::$%s', $class, $propertyName));
            }
            if (count($types) > 1) {
                throw new \LogicException(sprintf('Property %s::$%s defines more than one type.', $class, $propertyName));
            }

            $properties->get($propertyName)->setRef(
                $this->modelRegistry->register(new Model($types[0], $model->getGroups()))
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ModelInterface $model): bool
    {
        return $model instanceof Model && Type::BUILTIN_TYPE_OBJECT === $model->getType()->getBuiltinType();
    }
}
