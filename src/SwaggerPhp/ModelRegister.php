<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\SwaggerPhp;

use Swagger\Annotations\AbstractAnnotation;
use ZQuintana\LaraSwag\Annotation\Form as FormAnnotation;
use ZQuintana\LaraSwag\Annotation\Model as ModelAnnotation;
use ZQuintana\LaraSwag\Model\FormModel;
use ZQuintana\LaraSwag\Model\Model;
use ZQuintana\LaraSwag\Model\ModelRegistry;
use Swagger\Analysis;
use Swagger\Annotations\Items;
use Swagger\Annotations\Parameter;
use Swagger\Annotations\Response;
use Swagger\Annotations\Schema;
use Symfony\Component\PropertyInfo\Type;

/**
 * Resolves the path in SwaggerPhp annotation when needed.
 *
 * @internal
 */
final class ModelRegister
{
    /**
     * @var array
     */
    private $annotationMap = [
        ModelAnnotation::class => Model::class,
        FormAnnotation::class  => FormModel::class,
    ];

    /**
     * @var ModelRegistry
     */
    private $modelRegistry;


    /**
     * ModelRegister constructor.
     *
     * @param ModelRegistry $modelRegistry
     */
    public function __construct(ModelRegistry $modelRegistry)
    {
        $this->modelRegistry = $modelRegistry;
    }

    /**
     * @param Analysis $analysis
     */
    public function __invoke(Analysis $analysis)
    {
        foreach ($analysis->annotations as $annotation) {
            if (!$annotation instanceof AbstractAnnotation) {
                continue;
            }

            foreach ($annotation->_unmerged as $key => $unmerged) {
                if ($unmerged instanceof ModelAnnotation) {
                    $schema = $this->createModelSchema($annotation, $unmerged);
                    $annotation->merge([ $schema ]);

                    // It is no longer an unmerged annotation
                    unset($annotation->_unmerged[$key]);
                    $analysis->annotations->detach($unmerged);

                    break;
                }
            }

            if ($annotation instanceof FormAnnotation) {
                $this->processFormAnnotation($annotation);
            }
        }
    }

    /**
     * @param FormAnnotation $anno
     * @return AbstractAnnotation[]
     */
    private function processFormAnnotation(FormAnnotation $anno)
    {
        $form = new FormModel($anno->class, $anno->name, $anno->groups);

        return $anno->merge([
            'required' => true,
            'schema'   => new Schema([
                'ref' => $this->modelRegistry->register($form),
            ]),
        ]);
    }

    /**
     * @param AbstractAnnotation $annotation
     * @param ModelAnnotation    $anno
     *
     * @return Schema
     */
    private function createModelSchema(AbstractAnnotation $annotation, ModelAnnotation $anno)
    {
        if ($annotation instanceof Response) {
            $annotationClass = Schema::class;
        } elseif ($annotation instanceof Parameter) {
            if ('array' === $annotation->type) {
                $annotationClass = Items::class;
            } else {
                $annotationClass = Schema::class;
            }
        } elseif ($annotation instanceof Schema) {
            $annotationClass = Items::class;
        } else {
            return null;
        }

        if (!is_string($anno->type)) {
            // Ignore invalid annotations, they are validated later
            return null;
        }

        return new $annotationClass([
            'ref' => $this->modelRegistry->register(new Model($this->createType($anno->type), $anno->groups)),
        ]);
    }

    /**
     * @param string $type
     * @return Type
     */
    private function createType(string $type): Type
    {
        if ('[]' === substr($type, -2)) {
            return new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, null, $this->createType(substr($type, 0, -2)));
        }

        return new Type(Type::BUILTIN_TYPE_OBJECT, false, $type);
    }
}
