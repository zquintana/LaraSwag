<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag;

use EXSyst\Component\Swagger\Swagger;
use ZQuintana\LaraSwag\Describer\DescriberInterface;
use ZQuintana\LaraSwag\Describer\ModelRegistryAwareInterface;
use ZQuintana\LaraSwag\Model\ModelRegistry;
use ZQuintana\LaraSwag\ModelDescriber\ModelDescriberInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class ApiDocGenerator
 */
final class ApiDocGenerator
{
    /**
     * @var Swagger
     */
    private $swagger;

    /**
     * @var array|DescriberInterface[]
     */
    private $describers;

    /**
     * @var array|ModelDescriberInterface[]
     */
    private $modelDescribers;

    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * @var string
     */
    private $host;

    /**
     * @var array
     */
    private $security;


    /**
     * @param DescriberInterface[]      $describers
     * @param ModelDescriberInterface[] $modelDescribers
     * @param CacheItemPoolInterface    $cacheItemPool
     * @param array                     $security
     * @param string                    $host
     */
    public function __construct(
        array $describers,
        array $modelDescribers,
        CacheItemPoolInterface $cacheItemPool = null,
        array $security = [],
        $host = null
    ) {
        $this->describers = $describers;
        $this->modelDescribers = $modelDescribers;
        $this->cacheItemPool = $cacheItemPool;
        $this->security = $security;
        $this->host     = $host;
    }

    /**
     * @return Swagger
     */
    public function generate(): Swagger
    {
        if (null !== $this->swagger) {
            return $this->swagger;
        }

        if ($this->cacheItemPool) {
            $item = $this->cacheItemPool->getItem('swagger_doc');
            if ($item->isHit()) {
                return $this->swagger = $item->get();
            }
        }

        $data = [];
        if (!empty($this->security)) {
            $data['securityDefinitions'] = isset($this->security['definition']) ?
                $this->security['definition'] : [];
            $data['security'] = isset($this->security['global']) ?
                $this->security['global'] : null;
        }
        $this->swagger = new Swagger($data);
        if ($this->host) {
            $this->swagger->setHost($this->host);
        }

        $modelRegistry = new ModelRegistry($this->modelDescribers, $this->swagger);
        foreach ($this->describers as $describer) {
            if ($describer instanceof ModelRegistryAwareInterface) {
                $describer->setModelRegistry($modelRegistry);
            }

            $describer->describe($this->swagger);
        }
        $modelRegistry->registerDefinitions();

        if (isset($item)) {
            $this->cacheItemPool->save($item->set($this->swagger));
        }

        return $this->swagger;
    }
}