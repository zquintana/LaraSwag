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

/**
 * Class FunctionalTest
 */
class FunctionalTest extends WebTestCase
{
    public function testConfiguredDocumentation()
    {
        $this->assertEquals('My App', $this->getSwaggerDefinition()->getInfo()->getTitle());
    }
}
