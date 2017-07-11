<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\Routing;

use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;

/**
 * Class FilteredRouteCollectionBuilder
 */
final class FilteredRouteCollectionBuilder
{
    /**
     * @var array
     */
    private $pathPatterns;


    /**
     * FilteredRouteCollectionBuilder constructor.
     *
     * @param array $pathPatterns
     */
    public function __construct(array $pathPatterns = [])
    {
        $this->pathPatterns = $pathPatterns;
    }

    /**
     * @param array $routes
     *
     * @return RouteCollection
     */
    public function filter($routes): RouteCollection
    {
        $filteredRoutes = new RouteCollection();
        foreach ($routes as $name => $route) {
            if ($this->match($route)) {
                $filteredRoutes->add($route);
            }
        }

        return $filteredRoutes;
    }

    /**
     * @param Route $route
     * @return bool
     */
    private function match(Route $route): bool
    {
        foreach ($this->pathPatterns as $pathPattern) {
            if (preg_match('{'.$pathPattern.'}', $route->getPath())) {
                return true;
            }
        }

        return false;
    }
}
