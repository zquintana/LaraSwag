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

/**
 * Class RouteMetadataDescriber
 */
final class RouteMetadataDescriber implements RouteDescriberInterface
{
    use RouteDescriberTrait;

    /**
     * {@inheritdoc}
     */
    public function describe(Swagger $api, Route $route, \ReflectionMethod $reflectionMethod)
    {
        foreach ($this->getOperations($api, $route, $reflectionMethod) as $operation) {
            $operation->merge(['schemes' => [$route->secure() ? 'https' : 'http']]);

//            $requirements = $route->getRequirements();

            // Don't include host requirements
            foreach ($route->parameterNames() as $pathVariable) {
                if ('_format' === $pathVariable) {
                    continue;
                }

                $parameter = $operation->getParameters()->get($pathVariable, 'path');
                $parameter->setRequired(true);
                $parameter->setType('string');

//                if (isset($requirements[$pathVariable])) {
//                    $parameter->setFormat($requirements[$pathVariable]);
//                }
            }
        }
    }
}
