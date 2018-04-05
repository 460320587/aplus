<?php

namespace Someline\Models\Foundation;

use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Someline\Component\Category\Models\Traits\SomelineMorphToManyCategoryTrait;
use Someline\Component\File\Models\Traits\SomelineHasFileablesTrait;
use Someline\Image\Models\Traits\SomelineHasImageablesTrait;
use Someline\Models\BaseModel;
use Someline\Models\Traits\RelationRelatedUserTrait;
use Someline\Models\Traits\RelationUserTrait;

class Album extends BaseModel implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;
    use RelationUserTrait;
    use RelationRelatedUserTrait;
    use SomelineHasImageablesTrait;
    use SomelineMorphToManyCategoryTrait;

    const PAYMENT_TYPE_PER_HOUR = 1;
    const PAYMENT_TYPE_PERCENTAGE = 2;
    const PAYMENT_TYPE_BASE_PERCENTAGE = 3;

    const MORPH_NAME = 'Album';

    protected $table = 'albums';

    protected $primaryKey = 'album_id';

    protected $fillable = [
        'related_user_id',
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
        'payment_percentage',
        'someline_category_id',
        'keywords',
        'copyright',
        'status',
    ];

    // Fields to be converted to Carbon object automatically
    protected $dates = [];

    public function audios()
    {
        return $this->hasMany(Audio::class, 'album_id', 'album_id');
    }


    public static function getStatusTexts()
    {
        return [
            '0' => '连载中',
            '1' => '已完结'
        ];
    }

    public function getStatusTextAttribute()
    {
        $statusTexts = self::getStatusTexts();
        return $statusTexts[$this->status];
    }

}
