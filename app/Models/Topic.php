<?php

namespace App\Models;

/**
 * App\Models\Topic
 * @mixin \Eloquent
 */

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'title', 'body', 'order', 'excerpt', 'slug','category_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user (  ) : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category (  ) : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @param Builder $query
     * @param string $order
     * @return Builder
     */
    public function scopeWithOrder ( $query, $order ) : Builder
    {
        switch ( $order ){
            case 'recent': $query->recent(); break;
            default: $query->recentReplied(); break;
        }

        return $query->with('user', 'category');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeRecentReplied( $query) : Builder
    {
        return $query->orderBy('updated_at', 'desc');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeRecent( $query) : Builder
    {
        return $query->orderBy('created_at', 'desc');
    }
}
