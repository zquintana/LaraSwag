<?php

namespace ZQuintana\LaraSwag\Provider;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use ZQuintana\LaraSwag\Describer\SwaggerPhpDescriber;

/**
 * Class SwaggerProvider
 */
class SwaggerProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->bind('lara_swag.describers.swagger_php', function (Container $container) {
            return new SwaggerPhpDescriber(
                $container->make('lara_swag.routes'),
                $container->make('lara_swag.controller_reflector'),
                $container->make('lara_swag.annotation_reader')
            );
        });
        $this->app->tag([
            'lara_swag.describers.swagger_php',
        ], 'nelmio_api_doc.describer');
    }
}
