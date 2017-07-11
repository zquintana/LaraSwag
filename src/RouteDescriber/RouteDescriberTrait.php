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

use EXSyst\Component\Swagger\Operation;
use EXSyst\Component\Swagger\Swagger;
use Illuminate\Routing\Route;

/**
 * @internal
 */
trait RouteDescriberTrait
{
    /**
     * @internal
     *
     * @return Operation[]
     */
    private function getOperations(Swagger $api, Route $route): array
    {
        $path       = $api->getPaths()->get($this->normalizePath($route->getPath()));
        $methods    = $route->getMethods() ?: Swagger::$METHODS;
        $operations = [];

        foreach ($methods as $method) {
            $method = strtolower($method);
            if (!in_array($method, Swagger::$METHODS)) {
                continue;
            }

            $operations[] = $path->getOperation($method);
        }

        return $operations;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function normalizePath(string $path): string
    {
        if (substr($path, -10) === '.{_format}') {
            $path = substr($path, 0, -10);
        }

        return sprintf('/%s', $path);
    }
}
