<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\Tests\Describer;

use EXSyst\Component\Swagger\Swagger;
use Illuminate\Container\Container;
use ZQuintana\LaraSwag\Describer\RouteDescriber;
use ZQuintana\LaraSwag\RouteDescriber\RouteDescriberInterface;
use ZQuintana\LaraSwag\Util\ControllerReflector;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;

/**
 * Class RouteDescriberTest
 */
class RouteDescriberTest extends AbstractDescriberTest
{
    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $routeDescriber;


    public function testIgnoreWhenNoController()
    {
        $this->routes->add(new Route('GET', 'foo', function () {}));
        $this->routeDescriber->expects($this->never())
            ->method('describe');

        $this->assertEquals((new Swagger())->toArray(), $this->getSwaggerDoc()->toArray());
    }

    protected function setUp()
    {
        $this->routeDescriber = $this->createMock(RouteDescriberInterface::class);
        $this->routes = new RouteCollection();
        $this->describer = new RouteDescriber(
            $this->routes,
            new ControllerReflector(
                new Container()
            ),
            [$this->routeDescriber]
        );
    }
}
