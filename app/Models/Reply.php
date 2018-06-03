<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Reply
 * @package App\Models
 */
class Reply extends Model
{
    /**
     * @var array
     */
    protected $fillable = [ 'content'];

    /**
     * @return BelongsTo
     */
    public function user (  )
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function topic (  )
    {
        return $this->belongsTo(Topic::class);
    }
}
