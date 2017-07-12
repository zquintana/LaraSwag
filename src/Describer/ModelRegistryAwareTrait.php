<?php

namespace ZQuintana\LaraSwag\Describer;

use ZQuintana\LaraSwag\Model\ModelRegistry;

/**
 * Trait ModelRegistryAwareTrait
 */
trait ModelRegistryAwareTrait
{
    /**
     * @var ModelRegistry
     */
    private $modelRegistry;


    /**
     * @param ModelRegistry $modelRegistry
     *
     * @return $this
     */
    public function setModelRegistry(ModelRegistry $modelRegistry)
    {
        $this->modelRegistry = $modelRegistry;

        return $this;
    }
}
