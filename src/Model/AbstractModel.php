<?php

namespace ZQuintana\LaraSwag\Model;

/**
 * Class AbstractModel
 */
abstract class AbstractModel implements ModelInterface
{
    /**
     * @return string
     */
    public function getHash(): string
    {
        return spl_object_hash($this);
    }
}
