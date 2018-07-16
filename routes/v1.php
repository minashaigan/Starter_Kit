<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'profile_json_response', 'namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {

    // Auth
    $api->post('login', 'ApiAuthController@login');
    $api->post('logout', 'ApiAuthController@logout');
    $api->post('refresh', 'ApiAuthController@refresh');
    $api->post('me', 'ApiAuthController@me');

    // User
    $api->get('/users', ['as' => 'users.index', 'uses' => 'ApiUserController@index']);
    $api->get('/users/{id}', ['as' => 'users.show', 'uses' => 'ApiUserController@show']);


});

