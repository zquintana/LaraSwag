<?php

namespace ZQuintana\LaraSwag\Provider;

use Illuminate\Support\ServiceProvider;
use ZQuintana\LaraSwag\RouteDescriber\PhpDocDescriber;

/**
 * Class PhpDocProvider
 */
class PhpDocProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->make('lara_swag.route_describers.php_doc', function () {
            return new PhpDocDescriber();
        });
        $this->app->tag([
            'lara_swag.route_describers.php_doc',
        ], 'lara_swag.route_describer');
    }
}
