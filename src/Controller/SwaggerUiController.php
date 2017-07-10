<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\Controller;

use ZQuintana\LaraSwag\ApiDocGenerator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SwaggerUiController
 */
final class SwaggerUiController
{
    /**
     * @var ApiDocGenerator
     */
    private $apiDocGenerator;


    /**
     * SwaggerUiController constructor.
     *
     * @param ApiDocGenerator $apiDocGenerator
     */
    public function __construct(ApiDocGenerator $apiDocGenerator)
    {
        $this->apiDocGenerator = $apiDocGenerator;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('lara_swag::SwaggerUi.index', [
            'swagger_data' => [
                'spec' => $this->apiDocGenerator->generate()->toArray(),
            ],
        ]);
    }
}
