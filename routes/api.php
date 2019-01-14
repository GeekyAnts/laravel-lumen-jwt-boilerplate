<?php

use Dingo\Api\Routing\Router;

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

// Public APIs starts from here...
$api->post('register', 'Auth\RegisterController@register')->name('register');
$api->post('login', 'Auth\LoginController@login')->name('login');
$api->post('password/forgot', 'Auth\PasswordController@forgot')->name('password.forgot');

// Protected APIs starts from here...
$api->group([
    'middleware' => 'jwt.auth'
], function (Router $api) {

    // Handle user token...
    $api->post('refresh', 'Auth\TokenController@refresh')->name('refresh');
    $api->post('logout', 'Auth\TokenController@logout')->name('logout');

    // Handle user...
    $api->post('me', 'Auth\UserController@me')->name('user.show');
    $api->post('put-me', 'Auth\UserController@putMe')->name('user.update');
    $api->put('me/avatar', 'Auth\UserController@putMyAvatar')->name('user.update.avatar');
});
