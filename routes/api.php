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

$api->version('v1',['namespace'=>'App\Http\Controllers\Api','middleware' => ['serializer:array','bindings']],function ( $api ) {
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
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        /*** @var \Dingo\Api\Routing\Router $api */
        /*** 无需验证token的接口 */
        $api->get('categories', 'CategoriesController@index')->name('api.categories.index');
        $api->get('topics', 'TopicsController@index')->name('api.topics.index');
        $api->get('users/{user}/topics', 'TopicsController@userIndex')->name('api.users.topics.index');
        $api->get('topics/{topic}', 'TopicsController@show')->name('api.topics.show');
        $api->get('topics/{topic}/replies', 'RepliesController@index')->name('api.topics.replies.index');
        $api->get('users/{user}/replies', 'RepliesController@userIndex')->name('api.users.replies.index');
        /*** 需要 token 验证的接口 */
        $api->group(['middleware' => 'api.auth'], function($api) {
            /*** @var \Dingo\Api\Routing\Router $api */
            $api->get('user', 'UsersController@me')->name('api.user.show');
            $api->patch('user', 'UsersController@update')->name('api.user.update');
            $api->post('images', 'ImagesController@store')->name('api.images.store');
            $api->post('topics', 'TopicsController@store')->name('api.topics.store');
            $api->patch('topics/{topic}', 'TopicsController@update')->name('api.topics.update');
            $api->delete('topics/{topic}', 'TopicsController@destroy')->name('api.topics.destroy');
            $api->post('topics/{topic}/replies', 'RepliesController@store')->name('api.topics.replies.store');
            $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')->name('api.topics.replies.destroy');
            $api->get('user/notifications', 'NotificationsController@index')->name('api.user.notifications.index');
            $api->get('user/notifications/stats', 'NotificationsController@stats')->name('api.user.notifications.stats');
            $api->patch('user/read/notifications', 'NotificationsController@read')->name('api.user.notifications.read');
        });
    });
});
