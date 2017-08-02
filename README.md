Lara Swag
==================

[![Build
Status](https://secure.travis-ci.org/nelmio/NelmioApiDocBundle.png?branch=master)](http://travis-ci.org/nelmio/NelmioApiDocBundle)
[![Total Downloads](https://poser.pugx.org/nelmio/api-doc-bundle/downloads)](https://packagist.org/packages/nelmio/api-doc-bundle)
[![Latest Stable
Version](https://poser.pugx.org/nelmio/api-doc-bundle/v/stable)](https://packagist.org/packages/nelmio/api-doc-bundle)

The **LaraSwag** package allows you to generate a decent documentation
for your APIs.

## Installation

First, open a command console, enter your project directory and execute the following command to download the latest version of this bundle (still in beta, for a stable version look [here](https://github.com/nelmio/NelmioApiDocBundle/tree/2.x)):

```
composer require zquintana/lara-swag dev-master
```

Then add the service provider to your app config:
```php
ZQuintana\LaraSwag\Provider\LaraSwagProvider::class
```

To install the vendor assets like configurations and templates run:
```bash
$ php artisan vendor:publish --provider="ZQuintana\LaraSwag\Provider\LaraSwagProvider::class"
```


To browse your documentation with Swagger UI, register the routes in `config/routing/lara_swag.php`.
To make this easier after you run the `vendor:publish` command you can add 
the following to your routes config file: 

```yml
<?php
...
Route::group(['prefix' => 'api'], function () {
    require_once('lara_swag.php'); // use routes/lara_swag.php if you're using Laravel pre 5.3
});
```

## What does this bundle?

It generates you a swagger documentation from your Laravel app thanks to
_Describers_. Each of these _Describers_ extract infos from various sources.
For instance, one extract data from SwaggerPHP annotations, one from your
routes, etc.

If you configured the routes above, you can browse your documentation at 
`http://example.org/api/docs`.

## Use the bundle

You can configure globally your documentation in the config (take a look at
[the Swagger specification](http://swagger.io/specification/) to know the fields
available):

```php
<?php

return [
    'documentation' => [
        'info' => [
            'title'       => 'My App',
            'description' => 'This is an awesome app!',
            'version'     => '1.0.0',            
        ]    
    ],
];
```

To document your routes, you can use annotations in your controllers:

```php

namespace App\Controllers;

use App\Models\User;
use App\Models\Reward;
use ZQuintana\LaraSwag\Annotation\Model;
use Swagger\Annotations as SWG;

class UserController
{
    /*
     * @SWG\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Reward::class, groups={"full"})
     *     )
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     type="string",
     *     description="The field used to order rewards"
     * )
     * @SWG\Tag(name="rewards")
     */
    public function fetchUserRewardsAction(User $user)
    {
        // ...
    }
}
```

## What's supported?

This package supports _Laravel_ route requirements, PHP annotations,
[_Swagger-Php_](https://github.com/zircote/swagger-php) annotations.

It supports models through the ``@Model`` annotation.

## Contributing

See
[CONTRIBUTING](https://github.com/zquintana/laravel-webpack/blob/master/CONTRIBUTING.md)
file.

## Running the Tests

Install the [Composer](http://getcomposer.org/) dependencies:

    git clone https://github.com/zquintana/LaraSwag.git
    cd LaraSwag
    composer install

Then run the test suite:

    ./phpunit

## License

This bundle is released under the MIT license.
