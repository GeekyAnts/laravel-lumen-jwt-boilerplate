<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

use Dingo\Api\Routing\Router;

/**
 * Group with Throttle
 * @var Associative-Array $group
 */
$group = [
    'middleware' => 'api.throttle',
    'limit' => 60,
    'expires' => 1
];

/** 
 * @var Router $api 
 */
$api = app(Router::class);

$api->version('v1', $group, function (Router $api) {

    // Public APIs starts from here...
    $api->post('register', 'App\Http\Controllers\Api\Auth\RegisterController@register')->name('register');
    $api->post('login', 'App\Http\Controllers\Api\Auth\LoginController@login')->name('login');
    $api->post('password/forgot', 'App\Http\Controllers\Api\Auth\PasswordController@forgot')->name('password.forgot');

    // Protected APIs starts from here...
    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {

        // Handle user token...
        $api->post('refresh', 'App\Http\Controllers\Api\Auth\TokenController@refresh')->name('refresh');
        $api->post('logout', 'App\Http\Controllers\Api\Auth\TokenController@logout')->name('logout');

        // Handle user...
        $api->post('me', 'App\Http\Controllers\Api\Auth\UserController@me')->name('user.show');
        $api->post('put-me', 'App\Http\Controllers\Api\Auth\UserController@putMe')->name('user.update');
        $api->put('me/avatar', 'App\Http\Controllers\Api\Auth\UserController@putMyAvatar')->name('user.update.avatar');
   	});
});
