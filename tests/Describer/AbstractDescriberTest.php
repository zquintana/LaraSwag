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
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractDescriberTest
 */
abstract class AbstractDescriberTest extends TestCase
{
    protected $describer;


    /**
     * @return Swagger
     */
    protected function getSwaggerDoc(): Swagger
    {
        $api = new Swagger();
        $this->describer->describe($api);

        return $api;
    }
}
