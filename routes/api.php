<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app(\Dingo\Api\Routing\Router::class);

$api->version('v1',['namespace'=>'App\Http\Controllers\Api'],function ( $api ) {
    /*** @var \Dingo\Api\Routing\Router $api */
    $api->group([
        'middleware' => 'api.throttle',
        'limit'      => config('api.rate_limits.sign.limit'),
        'expires'    => config('api.rate_limits.sign.expires'),
        ],function ( $api ) {
        /*** @var \Dingo\Api\Routing\Router $api */
        /** 用户登录相关路由 */
        $api->post('verificationCodes','VerificationCodesController@store')->name('api.verificationCodes.store');
        $api->post('users', 'UsersController@store')->name('api.users.store');
        $api->post('captchas', 'CaptchasController@store')->name('api.captchas.store');
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')->name('api.socials.authorizations.store');
        $api->group(['prefix'=>'authorizations'],function ( $api ) {
            /*** @var \Dingo\Api\Routing\Router $api */
            $api->post('/', 'AuthorizationsController@store')->name('api.authorizations.store');
            $api->put('current', 'AuthorizationsController@update')->name('api.authorizations.update');
            $api->delete('current', 'AuthorizationsController@destroy')->name('api.authorizations.destroy');
        });

    });
});
