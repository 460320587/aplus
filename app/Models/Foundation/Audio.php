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

    const POOL_LARGE = 'large'; //大
    const POOL_UNDETERMINED = 'undetermined';//未确定的
    const POOL_REVIEW = 'review';//审核
    const POOL_APPROVED = 'approved';//批准

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
        'pool',
        'failed_times',
        'review_user_id',
        'reviewed_at',
        'zy_sync_at',
        'status',
    ];

    // Fields to be converted to Carbon object automatically
    protected $dates = [];

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id', 'album_id');
    }

    public function reviewer()
    {
        return $this->hasOne(User::class, 'user_id', 'review_user_id');
    }

    //获取最新的审核
    public function getLatestSomelineReview()
    {
        return $this->someline_reviews()->orderBy('created_at', 'desc')->first();
    }

    public function onCreated()
    {
        parent::onCreated();

        $sequence = $this->sequence;
        if ($sequence == 1 || $sequence == 2) {
            $this->updatePool(self::POOL_REVIEW);
        } else {
            $this->updatePool(self::POOL_LARGE);
        }

    }

    public function onUpdated()
    {
        /** @var Album $album */
        $album = $this->album;
        $album->doAutoApproveAudios();
    }

    public function onSaved()
    {
        parent::onSaved();

        $this->album->touch();
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
        if ($somelineReview->isApproved()) {
            if ($this->isPool(self::POOL_REVIEW)) {
                $this->updatePool(self::POOL_APPROVED);
            }
        } else {
            $this->failed_times += 1;
            $this->updatePool(self::POOL_REVIEW);
        }
        $this->review_user_id = $somelineReview->getUserId();
        $this->reviewed_at = $somelineReview->created_at;
        $this->status = $somelineReview->getReviewResult();
        $this->save();
    }

    public function isPool($pool)
    {
        return $this->pool == $pool;
    }

    public function isStatus($status)
    {
        return $this->status == $status;
    }

    public function updatePool($pool)
    {
        $this->pool = $pool;
        $this->save();
    }
}
