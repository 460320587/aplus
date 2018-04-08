<?php

namespace Someline\Models\Foundation;

use Illuminate\Database\Eloquent\SoftDeletes;
use Someline\Component\File\Models\Traits\SomelineHasOneFileTrait;
use Someline\Component\Review\Models\SomelineReviewInterface;
use Someline\Component\Review\Models\Traits\SomelineMorphManyReviewsTrait;
use Someline\Models\BaseModel;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Someline\Models\Review\SomelineReview;

class Audio extends BaseModel implements Transformable, SomelineReviewInterface
{
    use TransformableTrait;
    use SoftDeletes;
    use SomelineHasOneFileTrait;
    use SomelineMorphManyReviewsTrait;

    const MORPH_NAME = 'Audio';

    const STATUS_REJECTED = -1;
    const STATUS_NOT_REVIEWED = 0;
    const STATUS_APPROVED = 1;

    protected $table = 'audios';

    protected $primaryKey = 'audio_id';

    protected $fillable = [
        'user_id',
        'album_id',
        'name',
        'original_name',
        'someline_file_id',
        'audio_length',
        'audio_bitrate',
        'sequence',
        'status',
    ];

    // Fields to be converted to Carbon object automatically
    protected $dates = [];

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id', 'album_id');
    }

    //获取最新的审核
    public function getLatestSomelineReview()
    {
        return $this->someline_reviews()->orderBy('created_at', 'desc')->first();
    }

    public static function getStatusTexts()
    {
        return [
            self::STATUS_REJECTED => '审核未过',
            self::STATUS_NOT_REVIEWED => '未审核',
            self::STATUS_APPROVED => '已审核',
        ];
    }

    public function getStatusTextAttribute()
    {
        $statusTexts = self::getStatusTexts();
        return $statusTexts[$this->status];
    }

    public function onReviewed(SomelineReview $somelineReview)
    {
        $this->status = $somelineReview->getReviewResult();
        $this->save();
    }
}
