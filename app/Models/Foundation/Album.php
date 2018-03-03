<?php

namespace Someline\Models\Foundation;

use Someline\Models\BaseModel;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Album extends BaseModel implements Transformable
{
    use TransformableTrait;

    protected $table = 'albums';

    protected $primaryKey = 'album_id';

    protected $fillable = [
        'user_id',
        'book_id',
        'name',
        'author',
        'broadcaster',
        'broadcaster_type',
        'someline_image_id',
        'brief',
        'payment_type',
        'payment_amount',
        'someline_category_id',
        'keywords',
        'copyright',
        'status',
    ];

    // Fields to be converted to Carbon object automatically
    protected $dates = [];

}
