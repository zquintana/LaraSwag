<?php

namespace ZQuintana\LaraSwag\Provider;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use ZQuintana\LaraSwag\Describer\SwaggerPhpDescriber;
use ZQuintana\LaraSwag\Form\SwaggerExtension;

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
        $this
            ->registerPhpDescriber()
            ->registerFormExtension()
        ;
    }

    /**
     * @return SwaggerProvider
     */
    private function registerPhpDescriber(): SwaggerProvider
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
        ], 'lara_swag.describer');

        return $this;
    }

    /**
     * @return SwaggerProvider
     */
    private function registerFormExtension(): SwaggerProvider
    {
        $this->app->bind('lara_swag.form.swagger_extension', function (Container $container) {
            return new SwaggerExtension();
        });
        $this->app->extend('form.type.extensions', function ($extensions, Container $container) {
            array_push(
                $extensions,
                $container->make('lara_swag.form.swagger_extension')
            );

            return $extensions;
        });

        return $this;
    }
}
