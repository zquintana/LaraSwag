<?php

namespace ZQuintana\LaraSwag\Model;

/**
 * Interface ModelInterface
 */
interface ModelInterface
{
    /**
     * @return string
     */
    public function getHash(): string;

    /**
     * @return string
     */
    public function getName(): string;
}
