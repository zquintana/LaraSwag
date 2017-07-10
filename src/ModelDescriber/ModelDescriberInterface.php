<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\ModelDescriber;

use EXSyst\Component\Swagger\Schema;
use ZQuintana\LaraSwag\Model\Model;

/**
 * Interface ModelDescriberInterface
 */
interface ModelDescriberInterface
{
    /**
     * @param Model  $model
     * @param Schema $schema
     * @return mixed
     */
    public function describe(Model $model, Schema $schema);

    /**
     * @param Model $model
     * @return bool
     */
    public function supports(Model $model): bool;
}
