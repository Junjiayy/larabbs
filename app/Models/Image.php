<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 * @package App\Models
 */
class Image extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['type','path'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user (  )
    {
        return $this->belongsTo(User::class);
    }
}
