<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasRoles,Notifiable {
        notify as protected laravelNotify;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics () : HasMany
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * @return HasMany
     */
    public function replies (  ) : HasMany
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * @param Model $model
     * @return bool
     */
    public function isAuthorOf ( $model )
    {
        return $this->id == $model->user_id;
    }

    public function notify( $instance )
    {
        if ( $this->id == Auth::id() ) {
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function setPasswordAttribute( $value )
    {
        if ( strlen($value) != 60 ) {
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path)
    {
        if ( ! starts_with($path, 'http')) {
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }
}
