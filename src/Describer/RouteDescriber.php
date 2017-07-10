<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\Describer;

use EXSyst\Component\Swagger\Swagger;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use ZQuintana\LaraSwag\RouteDescriber\RouteDescriberInterface;
use ZQuintana\LaraSwag\Util\ControllerReflector;

/**
 * Class RouteDescriber
 */
final class RouteDescriber implements DescriberInterface, ModelRegistryAwareInterface
{
    use ModelRegistryAwareTrait;

    private $routeCollection;
    private $controllerReflector;
    private $routeDescribers;

    /**
     * @param RouteCollection           $routeCollection
     * @param ControllerReflector       $controllerReflector
     * @param RouteDescriberInterface[] $routeDescribers
     */
    public function __construct(RouteCollection $routeCollection, ControllerReflector $controllerReflector, array $routeDescribers)
    {
        $this->routeCollection = $routeCollection;
        $this->controllerReflector = $controllerReflector;
        $this->routeDescribers = $routeDescribers;
    }

    /**
     * {@inheritdoc}
     */
    public function describe(Swagger $api)
    {
        if (0 === count($this->routeDescribers)) {
            return;
        }

        foreach ($this->routeCollection as $route) {
            /** @var Route $route */
            if ($route->getActionName() instanceof \Closure) {
                continue;
            }

            // if able to resolve the controller
            $controller = $route->getActionName();
            if ($method = $this->controllerReflector->getReflectionMethod($controller)) {
                // Extract as many informations as possible about this route
                foreach ($this->routeDescribers as $describer) {
                    if ($describer instanceof ModelRegistryAwareInterface) {
                        $describer->setModelRegistry($this->modelRegistry);
                    }

                    $describer->describe($api, $route, $method);
                }
            }
        }
    }
}
