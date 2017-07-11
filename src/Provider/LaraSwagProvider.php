<?php

namespace ZQuintana\LaraSwag\Provider;

use Doctrine\Common\Annotations\AnnotationReader;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use ZQuintana\LaraSwag\ApiDocGenerator;
use ZQuintana\LaraSwag\Controller\SwaggerUiController;
use ZQuintana\LaraSwag\Describer\DefaultDescriber;
use ZQuintana\LaraSwag\Describer\ExternalDocDescriber;
use ZQuintana\LaraSwag\Describer\RouteDescriber;
use ZQuintana\LaraSwag\ModelDescriber\CollectionModelDescriber;
use ZQuintana\LaraSwag\ModelDescriber\FormModelDescriber;
use ZQuintana\LaraSwag\ModelDescriber\ObjectModelDescriber;
use ZQuintana\LaraSwag\ModelDescriber\ScalarModelDescriber;
use ZQuintana\LaraSwag\RouteDescriber\RouteMetadataDescriber;
use ZQuintana\LaraSwag\Routing\FilteredRouteCollectionBuilder;
use ZQuintana\LaraSwag\Util\ControllerReflector;

/**
 * Class LaraSwagProvider
 */
class LaraSwagProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->registerViews();
        $this->publishes([
            __DIR__.'/../../config/lara_swag.php' => config_path('lara_swag.php'),
            __DIR__.'/../../config/routing/lara_swag.php' => app_path('Http/routes/lara_swag.php'),
        ], 'config');
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $configPath = __DIR__.'/../../config/lara_swag.php';
        $this->mergeConfigFrom($configPath, 'lara_swag');

        $this->app->register(PhpDocProvider::class);
        $this->app->register(SwaggerProvider::class);

        $this->registerUtils()
            ->registerDescribers()
            ->registerController()
            ->registerGenerator()
        ;
    }

    /**
     * @return $this
     */
    private function registerController()
    {
        $this->app->bind('lara_swag.routes', function (Container $container) {
            $config = $container->make('config');
            $patterns = $config->get('lara_swag.routes.path_patterns');
            $routes   = $container->make('router')->getRoutes();

            if (count($patterns)) {
                $filteredCollection = new FilteredRouteCollectionBuilder(
                    $patterns
                );

                return $filteredCollection->filter($routes);
            }

            return $routes;
        });
        $this->app->bind('lara_swag.controller.swagger_ui', function (Container $container) {
            return new SwaggerUiController(
                $container->make('lara_swag.generator')
            );
        });
        $this->app->alias('lara_swag.controller.swagger_ui', SwaggerUiController::class);

        return $this;
    }

    /**
     * @return $this
     */
    private function registerGenerator()
    {
        $this->app->singleton('lara_swag.generator', function (Container $container) {
            $config = $container->make('config');

            return new ApiDocGenerator(
                $container->tagged('lara_swag.describer'),
                $container->tagged('lara_swag.model_describers'),
                null,
                $config->get('lara_swag.security', []),
                $config->get('lara_swag.routes.host')
            );
        });

        return $this;
    }

    /**
     * @return $this
     */
    private function registerDescribers()
    {
        $this->app->bind('lara_swag.controller_reflector', function (Container $container) {
            return new ControllerReflector($container);
        });

        // Describers
        $this->app->bind('lara_swag.describers.config', function (Container $container) {
            $config = $container->make('config');

            return new ExternalDocDescriber($config->get('lara_swag.documentation'));
        });
        $this->app->bind('lara_swag.describers.route', function (Container $container) {
            return new RouteDescriber(
                $container->make('lara_swag.routes'),
                $container->make('lara_swag.controller_reflector'),
                $container->tagged('lara_swag.route_describer')
            );
        });
        $this->app->bind('lara_swag.describers.default', function () {
            return new DefaultDescriber();
        });
        $this->app->tag([
            'lara_swag.describers.config',
            'lara_swag.describers.route',
            'lara_swag.describers.default',
        ], 'lara_swag.describer');

        // Routing Describers
        $this->app->bind('lara_swag.route_describers.route_metadata', function () {
            return new RouteMetadataDescriber();
        });
        $this->app->tag([
            'lara_swag.route_describers.route_metadata',
        ], 'lara_swag.route_describer');

        // Model Describers
        $this->app->bind('lara_swag.model_describers.object', function (Container $container) {
            return new ObjectModelDescriber(
                $container->make('lara_swag.property_info')
            );
        });
        $this->app->bind('lara_swag.model_describers.collection', function () {
            return new CollectionModelDescriber();
        });
        $this->app->bind('lara_swag.model_describers.scalar', function () {
            return new ScalarModelDescriber();
        });
        $modelDescribers = [
            'lara_swag.model_describers.object',
            'lara_swag.model_describers.collection',
            'lara_swag.model_describers.scalar',
        ];

        if ($this->app->bound(FormFactoryInterface::class)) {
            $this->app->bind('lara_swag.model_describers.form', function (Container $container) {
                return new FormModelDescriber($container->make(FormFactoryInterface::class));
            });
            $modelDescribers[] = 'lara_swag.model_describers.form';
        }
        $this->app->tag($modelDescribers, 'lara_swag.model_describers');

        return $this;
    }

    /**
     * @return $this
     */
    private function registerUtils()
    {
        $this->app->bind('lara_swag.property_info', function () {
            $phpDocExtractor     = new PhpDocExtractor();
            $reflectionExtractor = new ReflectionExtractor();

            return new PropertyInfoExtractor(
                [$reflectionExtractor],
                [$phpDocExtractor, $reflectionExtractor],
                [$phpDocExtractor],
                [$reflectionExtractor]
            );
        });

        $this->app->bind('lara_swag.annotation_reader', function () {
            return new AnnotationReader();
        });

        return $this;
    }

    /**
     * @return $this
     */
    private function registerViews()
    {
        $viewPath = resource_path('views/vendor/lara_swag');

        $sourcePath = __DIR__.'/../../resources/views';

        $this->publishes([
            $sourcePath => $viewPath,
            __DIR__.'/../../resources/public' => public_path('vendor/lara_swag'),
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path.'/lara_swag';
        }, \Config::get('view.paths')), [$sourcePath]), 'lara_swag');

        return $this;
    }
}
