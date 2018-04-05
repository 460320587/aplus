<?php

namespace Someline\Models\Foundation;

use Illuminate\Database\Eloquent\SoftDeletes;
use Someline\Component\File\Models\Traits\SomelineHasOneFileTrait;
use Someline\Models\BaseModel;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Audio extends BaseModel implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;
    use SomelineHasOneFileTrait;

    const STATUS_NOT_REVIEWED = 0;

    protected $table = 'audios';

    protected $primaryKey = 'audio_id';

    protected $fillable = [
        'user_id',
        'album_id',
        'name',
        'original_name',
        'someline_file_id',
        'audio_length',
        'sequence',
        'status',
    ];

    // Fields to be converted to Carbon object automatically
    protected $dates = [];

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id', 'album_id');
    }

    public static function getStatusTexts()
    {
        return [
            self::STATUS_NOT_REVIEWED => '未审核',
        ];
    }

    public function getStatusTextAttribute()
    {
        $statusTexts = self::getStatusTexts();
        return $statusTexts[$this->status];
    }

}
