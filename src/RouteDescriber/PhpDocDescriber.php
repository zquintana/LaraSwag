<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\RouteDescriber;

use EXSyst\Component\Swagger\Swagger;
use Illuminate\Routing\Route;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

/**
 * Class PhpDocDescriber
 */
final class PhpDocDescriber implements RouteDescriberInterface
{
    use RouteDescriberTrait;

    /**
     * @var DocBlockFactory|DocBlockFactoryInterface
     */
    private $docBlockFactory;

    /**
     * PhpDocDescriber constructor.
     * @param DocBlockFactoryInterface|null $docBlockFactory
     */
    public function __construct(DocBlockFactoryInterface $docBlockFactory = null)
    {
        if (null === $docBlockFactory) {
            $docBlockFactory = DocBlockFactory::createInstance();
        }
        $this->docBlockFactory = $docBlockFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function describe(Swagger $api, Route $route, \ReflectionMethod $reflectionMethod)
    {
        $classDocBlock = null;
        $docBlock = null;

        try {
            $classDocBlock = $this->docBlockFactory->create($reflectionMethod->getDeclaringClass());
        } catch (\Exception $e) {
        }
        try {
            $docBlock = $this->docBlockFactory->create($reflectionMethod);
        } catch (\Exception $e) {
        }

        foreach ($this->getOperations($api, $route, $reflectionMethod) as $operation) {
            if (null !== $docBlock) {
                if (null === $operation->getSummary() && '' !== $docBlock->getSummary()) {
                    $operation->setSummary($docBlock->getSummary());
                }
                if (null === $operation->getDescription() && '' !== (string) $docBlock->getDescription()) {
                    $operation->setDescription((string) $docBlock->getDescription());
                }
                if ($docBlock->hasTag('deprecated')) {
                    $operation->setDeprecated(true);
                }
            }
            if (null !== $classDocBlock) {
                if ($classDocBlock->hasTag('deprecated')) {
                    $operation->setDeprecated(true);
                }
            }
        }
    }
}
