<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nelmio\ApiDocBundle\Controller;

use Nelmio\ApiDocBundle\ApiDocGenerator;
use Symfony\Component\HttpFoundation\Response;

final class SwaggerUiController
{
    private $apiDocGenerator;
    private $twig;

    public function __construct(ApiDocGenerator $apiDocGenerator, \Twig_Environment $twig)
    {
        $this->apiDocGenerator = $apiDocGenerator;
        $this->twig = $twig;
    }

    public function __invoke()
    {
        return new Response(
            $this->twig->render('@NelmioApiDoc/SwaggerUi/index.html.twig', ['swagger_data' => ['spec' => $this->apiDocGenerator->generate()->toArray()]]),
            Response::HTTP_OK,
            ['Content-Type' => 'text/html']
        );
    }
}
