<?php

Route::get('/docs', 'ZQuintana\LaraSwag\Controller\SwaggerUiController@index');
Route::get('/docs/spec', 'ZQuintana\LaraSwag\Controller\SwaggerUiController@spec')
    ->name('lara_swag.doc.spec')
;
