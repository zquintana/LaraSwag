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
use ZQuintana\LaraSwag\Model\ModelInterface;

/**
 * Interface ModelDescriberInterface
 */
interface ModelDescriberInterface
{
    /**
     * @param ModelInterface $model
     * @param Schema         $schema
     * @return mixed
     */
    public function describe(ModelInterface $model, Schema $schema);

    /**
     * @param ModelInterface $model
     * @return bool
     */
    public function supports(ModelInterface $model): bool;
}
