<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\Util;

use Illuminate\Contracts\Container\Container;

/**
 * ControllerReflector
 */
final class ControllerReflector
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var array
     */
    private $controllers = [];


    /**
     * ControllerReflector constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Returns the ReflectionMethod for the given controller string.
     *
     * @param string $action
     *
     * @return \ReflectionMethod|null
     */
    public function getReflectionMethod(string $action)
    {
        $callable = $this->getClassAndMethod($action);
        if (null === $callable) {
            return null;
        }

        list($class, $method) = $callable;
        try {
            return new \ReflectionMethod($class, $method);
        } catch (\ReflectionException $e) {
            // In case we can't reflect the controller, we just
            // ignore the route
        }

        return null;
    }

    /**
     * @param string $controller
     *
     * @return array|null
     */
    public function getReflectionClassAndMethod(string $controller)
    {
        $callable = $this->getClassAndMethod($controller);
        if (null === $callable) {
            return null;
        }

        list($class, $method) = $callable;
        try {
            return [new \ReflectionClass($class), new \ReflectionMethod($class, $method)];
        } catch (\ReflectionException $e) {
            // In case we can't reflect the controller, we just
            // ignore the route
        }

        return null;
    }

    /**
     * @param string $action
     *
     * @return array|mixed|null
     */
    private function getClassAndMethod(string $action)
    {
        if (isset($this->controllers[$action])) {
            return $this->controllers[$action];
        }

        if (!preg_match('#(.+)@([\w]+)#', $action, $matches)) {
            return null;
        }

        $class = $matches[1];
        $method = $matches[2];

        if (!isset($class) || !isset($method)) {
            $this->controllers[$class] = null;

            return null;
        }

        return $this->controllers[$class] = [$class, $method];
    }
}
