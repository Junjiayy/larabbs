<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Cache;
use Auth;
use App\Models\Image;

/**
 * Class UsersController
 * @package App\Http\Controllers\Api
 */
class UsersController extends Controller
{

    /**
     * @param UserRequest $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function store ( UserRequest $request )
    {
        $verifyData = Cache::get($request->verification_key);

        if ( !$verifyData ) {
            return $this->response->error('验证码已失效', 422);
        }

        if ( !hash_equals($verifyData['code'], $request->verification_code) ) {
            // 返回401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name'     => $request->name,
            'phone'    => $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);

        Cache::forget($request->verification_key);

        return $this->response->item($user, new UserTransformer())->setMeta([
            'access_token' => Auth::guard('api')->fromUser($user),
            'token_type'   => 'Bearer',
            'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60
        ])->setStatusCode(201);
    }

    public function me ()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }

    public function update ( UserRequest $request )
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'email', 'introduction']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }
}
