<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\Tests\Functional;

use EXSyst\Component\Swagger\Operation;
use EXSyst\Component\Swagger\Schema;
use EXSyst\Component\Swagger\Swagger;

/**
 * Class WebTestCase
 */
class WebTestCase extends TestCase
{
    /**
     * @return Swagger
     */
    protected function getSwaggerDefinition()
    {
        return $this->app->make('lara_swag.generator')->generate();
    }

    /**
     * @param $name
     * @return Schema
     */
    protected function getModel($name): Schema
    {
        $definitions = $this->getSwaggerDefinition()->getDefinitions();
        $this->assertTrue($definitions->has($name));

        return $definitions->get($name);
    }

    protected function getOperation($path, $method): Operation
    {
        $api = $this->getSwaggerDefinition();
        $paths = $api->getPaths();

        $this->assertTrue($paths->has($path), sprintf('Path "%s" does not exist.', $path));
        $action = $paths->get($path);

        $this->assertTrue($action->hasOperation($method), sprintf('Operation "%s" for path "%s" does not exist', $path, $method));

        return $action->getOperation($method);
    }
}
