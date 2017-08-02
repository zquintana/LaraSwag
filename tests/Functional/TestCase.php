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

use Orchestra\Testbench\TestCase as BaseTestCase;
use ZQuintana\LaraSwag\Provider\LaraSwagProvider;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    public function getPackageProviders($app)
    {
        return [
            LaraSwagProvider::class,
        ];
    }

    public function setUp()
    {
        parent::setUp();

        require_once __DIR__.'/../../config/routing/lara_swag.php';
    }
}
