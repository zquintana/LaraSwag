<?php

namespace ZQuintana\LaraSwag\ModelDescriber;

use EXSyst\Component\Swagger\Schema;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use ZQuintana\LaraSwag\Describer\ModelRegistryAwareInterface;
use ZQuintana\LaraSwag\Describer\ModelRegistryAwareTrait;
use ZQuintana\LaraSwag\Model\FormModel;
use ZQuintana\LaraSwag\Model\ModelInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @internal
 */
final class FormModelDescriber implements ModelDescriberInterface, ModelRegistryAwareInterface
{
    use ModelRegistryAwareTrait;

    private $formFactory;

    /**
     * FormModelDescriber constructor.
     * @param FormFactoryInterface|null $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory = null)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function describe(ModelInterface $model, Schema $schema)
    {
        if (method_exists('Symfony\Component\Form\AbstractType', 'setDefaultOptions')) {
            throw new \LogicException('symfony/form < 3.0 is not supported, please upgrade to an higher version to use a form as a model.');
        }
        if (null === $this->formFactory) {
            throw new \LogicException('You need to enable forms in your application to use a form as a model.');
        }

        /** @var \ZQuintana\LaraSwag\Model\FormModel $model */
        $schema->setType('object');
        $class = $model->getClass();

        $form = $this->formFactory->create($class, null, []);
        $this->parseForm($schema, $form);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ModelInterface $model): bool
    {
        return $model instanceof \ZQuintana\LaraSwag\Model\FormModel;
    }

    /**
     * @param Schema        $schema
     * @param FormInterface $form
     *
     * @return void
     */
    private function parseForm(Schema $schema, $form)
    {
        $properties = $schema->getProperties();
        foreach ($form as $name => $child) {
            /** @var Form $child */
            $config = $child->getConfig();
            $property = $properties->get($name);

            if ($config->getCompound()) {
                $innerClass = get_class($config->getType()->getInnerType());
                $property->setType('object');
                $property->setRef($this->modelRegistry->register(new FormModel($innerClass)));

                continue;
            }

            for ($type = $config->getType(); null !== $type; $type = $type->getParent()) {
                $blockPrefix = $type->getBlockPrefix();

                if ('text' === $blockPrefix) {
                    $property->setType('string');
                    break;
                }
                if ('date' === $blockPrefix) {
                    $property->setType('string');
                    $property->setFormat('date');
                    break;
                }
                if ('datetime' === $blockPrefix) {
                    $property->setType('string');
                    $property->setFormat('date-time');
                    break;
                }
                if ('choice' === $blockPrefix) {
                    $property->setType('string');
                    if (($choices = $config->getOption('choices')) && is_array($choices) && count($choices)) {
                        $property->setEnum(array_values($choices));
                    }

                    break;
                }
                if ('entity' === $blockPrefix) {
                    $type = $config->getOption('swagger_type') ?? 'integer';
                    $property->setType($config->getOption('multiple') ? 'array' : $type);
                    if (null !== ($description = $config->getOption('swagger_description'))) {
                        $property->setDescription($description);
                    }
                    $items = $property->getItems();
                    $items->setType($type);

                    break;
                }
                if ('collection' === $blockPrefix) {
                    $subType = $config->getOption('entry_type');
                }
            }

            if ($config->getRequired()) {
                $required = $schema->getRequired() ?? [];
                $required[] = $name;

                $schema->setRequired($required);
            }
        }
    }
}
