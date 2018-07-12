<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZQuintana\LaraSwag\RouteDescriber;

use EXSyst\Component\Swagger\Operation;
use EXSyst\Component\Swagger\Parameter;
use EXSyst\Component\Swagger\Schema;
use EXSyst\Component\Swagger\Swagger;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Validator;

/**
 * @internal
 */
trait RouteDescriberTrait
{
    /**
     * @param Swagger           $api
     * @param Route             $route
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return Operation[]
     */
    private function getOperations(Swagger $api, Route $route, \ReflectionMethod $reflectionMethod): array
    {
        $formRequest = $this->getFormRequestParam($reflectionMethod);
        $path        = $api->getPaths()->get($this->normalizePath($route->uri()));
        $methods     = $route->methods() ?: Swagger::$METHODS;
        $operations  = [];

        if ($formRequest && $model = $this->transformFormRequest($formRequest)) {
            $api->getDefinitions()->merge($model);
        }

        foreach ($methods as $method) {
            $method = strtolower($method);
            if (!in_array($method, Swagger::$METHODS)) {
                continue;
            }

            $operation = $path->getOperation($method);
            if (isset($model)) {
                $operation->getParameters()->add(new Parameter([
                    'in' => 'body',
                    'name' => 'body',
                    'required' => true,
                    'schema' => [
                        '$ref' => sprintf('#/definitions/%s', get_class($formRequest)),
                    ],
                ]));
            }
            $operations[] = $operation;
        }

        return $operations;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function normalizePath(string $path): string
    {
        if (substr($path, -10) === '.{_format}') {
            $path = substr($path, 0, -10);
        }

        return sprintf('/%s', $path);
    }

    /**
     * @param FormRequest $request
     * @return array|null
     */
    private function transformFormRequest(FormRequest $request)
    {
        if (!method_exists($request, 'rules')) {
            return null;
        }

        /** @var Validator $validator */
        $validator = app(Factory::class)->make([], app()->call([$request, 'rules']));

        $required = [];
        $params = [];
        foreach ($validator->getRules() as $name => $validations) {
            if (in_array('required', $validations)) {
                $required[] = $name;
            }

            $params[$name] = [
                'type' => 'string',
            ];
        }

        return [
            get_class($request) => [
                'type' => 'object',
                'properties' => $params,
            ],
        ];
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     * @return null|object|FormRequest
     */
    private function getFormRequestParam(\ReflectionMethod $reflectionMethod)
    {
        foreach ($reflectionMethod->getParameters() as $argument) {
            $class = $argument->getClass();
            if ($class && $class->isSubclassOf(FormRequest::class)) {
                $current = app('request');

                /** @var FormRequest $form */
                $form = $class->newInstanceWithoutConstructor();
                $form->initialize(
                    $current->query->all(),
                    $current->request->all(),
                    $current->attributes->all(),
                    $current->cookies->all(),
                    [],
                    $current->server->all(),
                    null
                );
                $form->setContainer(app());

                return $form;
            }
        }

        return null;
    }
}
