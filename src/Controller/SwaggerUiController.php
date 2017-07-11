<?php

namespace ZQuintana\LaraSwag\Controller;

use Illuminate\Routing\Controller;
use ZQuintana\LaraSwag\ApiDocGenerator;

/**
 * Class SwaggerUiController
 */
final class SwaggerUiController extends Controller
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
