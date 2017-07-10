<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\Describer;

use ZQuintana\LaraSwag\Model\ModelRegistry;

interface ModelRegistryAwareInterface
{
    public function setModelRegistry(ModelRegistry $modelRegistry);
}
