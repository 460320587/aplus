<?php

namespace Someline\Models\Foundation;

use Someline\Models\BaseModel;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Audio extends BaseModel implements Transformable
{
    use TransformableTrait;

    protected $table = 'audios';

    protected $primaryKey = 'audio_id';

    protected $fillable = [
        'user_id',
        'name',
        'original_name',
        'someline_file_id',
        'audio_length',
        'sequence',
        'status',
    ];

    // Fields to be converted to Carbon object automatically
    protected $dates = [];

}
