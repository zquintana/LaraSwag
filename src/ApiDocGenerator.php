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
use Illuminate\Contracts\Cache\Repository;
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
    const CACHE_KEY = 'lara_swag.doc_spec';

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
     * @var Repository
     */
    private $cacheRepository;

    /**
     * @var string
     */
    private $host;

    /**
     * @var array
     */
    private $security;

    /**
     * @var array
     */
    private $info;


    /**
     * @param DescriberInterface[]      $describers
     * @param ModelDescriberInterface[] $modelDescribers
     * @param Repository                $cacheRepository
     * @param array                     $security
     * @param array                     $info
     * @param string                    $host
     */
    public function __construct(
        array $describers,
        array $modelDescribers,
        Repository $cacheRepository = null,
        array $security = [],
        array $info = [],
        $host = null
    ) {
        $this->describers      = $describers;
        $this->modelDescribers = $modelDescribers;
        $this->cacheRepository = $cacheRepository;
        $this->security        = $security;
        $this->info            = $info;
        $this->host            = $host;
    }

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getSpecArray(): array
    {
        return $this->cacheRepository->rememberForever(self::CACHE_KEY, function () {
            return $this->generate()->toArray();
        });
    }

    /**
     * @return Swagger
     */
    public function generate(): Swagger
    {
        if (null !== $this->swagger) {
            return $this->swagger;
        }

        $data = [
            'info' => $this->info,
        ];
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

        return $this->swagger;
    }
}
