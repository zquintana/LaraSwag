<?php

return [
    'documentation' => [
        'info' => [
            'title'       => 'My App',
            'description' => 'My awesome app',
            'version'     => '1.0.0',
        ],
    ],

    'routes' => [
        /**
         * Patterns you want to document
         */
        'path_patterns' => [],

        'host' => null,
    ],

    'security' => [],

    'ui_template' => 'lara_swag::SwaggerUi.index',
];
