<?php

namespace App\Models;

/**
 * App\Models\Topic
 * @mixin \Eloquent
 */
/**
 * Class Topic
 * @package App\Models
 */
class Topic extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'title', 'body', 'user_id', 'category_id', 'reply_count', 'view_count',
        'last_reply_user_id', 'order', 'excerpt', 'slug'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user (  )
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category (  )
    {
        return $this->belongsTo(Category::class);
    }
}
