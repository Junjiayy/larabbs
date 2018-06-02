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
    public function user (  ) : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function topic (  ) : BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
