<?php

namespace ZQuintana\LaraSwag\Command;

use Illuminate\Console\Command;
use ZQuintana\LaraSwag\ApiDocGenerator;

/**
 * Class CacheSpecCommand
 */
class CacheSpecCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'lara_swag:cache:spec';

    /**
     * @var string
     */
    protected $description = 'Generator doc spec and cache it';

    /**
     * @var ApiDocGenerator
     */
    private $generator;


    /**
     * CacheSpecCommand constructor.
     * @param ApiDocGenerator $apiDocGenerator
     */
    public function __construct(ApiDocGenerator $apiDocGenerator)
    {
        $this->generator = $apiDocGenerator;

        parent::__construct();
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function handle()
    {
        // Generates and caches spec
        $this->generator->getSpecArray();
        $this->output->success('Cached with configured driver');
    }
}
