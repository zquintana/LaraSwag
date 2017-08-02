<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\Tests\Routing;

use Illuminate\Routing\Route;
use ZQuintana\LaraSwag\Routing\FilteredRouteCollectionBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Tests for FilteredRouteCollectionBuilder class.
 */
class FilteredRouteCollectionBuilderTest extends TestCase
{
    public function testFilter()
    {
        $pathPattern = [
            '^/api/foo',
            '^/api/bar',
        ];

        $routes = [];
        $routes['r1'] = new Route('GET', '/api/bar/action1', function () {});
        $routes['r2'] = new Route('GET', '/api/foo/action1', function () {});
        $routes['r3'] = new Route('GET', '/api/foo/action2', function () {});
        $routes['r4'] = new Route('GET', '/api/demo', function () {});
        $routes['r5'] = new Route('GET', '/_profiler/test/test', function () {});

        $routeBuilder = new FilteredRouteCollectionBuilder($pathPattern);
        $filteredRoutes = $routeBuilder->filter($routes);

        $this->assertCount(3, $filteredRoutes);
    }
}
