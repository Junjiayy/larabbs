<?php
/**
 * Created by PhpStorm.
 * User: reliy
 * Date: 2018/6/5
 * Time: 上午12:59
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    public function transform ( User $user )
    {
        return [
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'avatar'          => $user->avatar,
            'introduction'    => $user->introduction,
            'bound_phone'     => $user->phone ? true : false,
            'bound_wechat'    => ( $user->weixin_unionid || $user->weixin_openid ) ? true : false,
            'last_actived_at' => $user->last_actived_at->toDateTimeString(),
            'created_at'      => $user->created_at->toDateTimeString(),
            'updated_at'      => $user->updated_at->toDateTimeString(),
        ];
    }
}