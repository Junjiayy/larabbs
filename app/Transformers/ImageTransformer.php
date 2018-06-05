<?php
/**
 * Created by PhpStorm.
 * User: reliy
 * Date: 2018/6/5
 * Time: 上午11:56
 */

namespace App\Transformers;

use App\Models\Image;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    public function transform ( Image $image )
    {
        return [
            'id'         => $image->id,
            'user_id'    => $image->user_id,
            'type'       => $image->type,
            'path'       => $image->path,
            'created_at' => $image->created_at->toDateTimeString(),
            'updated_at' => $image->updated_at->toDateTimeString(),
        ];
    }
}