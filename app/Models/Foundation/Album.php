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

    const MORPH_NAME = 'Album';

    const PAYMENT_TYPE_PER_HOUR = 1;
    const PAYMENT_TYPE_PERCENTAGE = 2;
    const PAYMENT_TYPE_BASE_PERCENTAGE = 3;

    const STATUS_PUBLISHING = 0;
    const STATUS_ENDED = 1;

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
        'audio_bitrate',
        'weight',
        'status',
    ];

    // Fields to be converted to Carbon object automatically
    protected $dates = [];

    public function audios()
    {
        return $this->hasMany(Audio::class, 'album_id', 'album_id');
    }

    public function ordered_audios($order = 'asc')
    {
        return $this->audios()->orderBy('sequence', $order);
    }

    public function rejected_audios()
    {
        return $this->audios()->where('status', Audio::STATUS_REJECTED);
    }

    public function onUpdated()
    {
        if ($this->isStatus(self::STATUS_ENDED)) {
            $this->updateUndeterminedAudios();
        }
    }

    protected function updateUndeterminedAudios()
    {
        $this->audios()->where('pool', Audio::POOL_UNDETERMINED)
            ->update([
                'pool' => Audio::POOL_REVIEW,
            ]);
    }

    public function isStatus($status)
    {
        return $this->status == $status;
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

    public static function getPaymentTypeTexts()
    {
        return [
            self::PAYMENT_TYPE_PER_HOUR => '买断',
            self::PAYMENT_TYPE_PERCENTAGE => '分成',
            self::PAYMENT_TYPE_BASE_PERCENTAGE => '保底分成',
        ];
    }

    public function getPaymentTypeTextAttribute()
    {
        $paymentTypeTexts = self::getPaymentTypeTexts();
        return $paymentTypeTexts[$this->payment_type];
    }


    public function getCategoryText()
    {
        $categories_name = $this->someline_categories->pluck('category_name')->toArray();
        if (!empty($categories_name)) {
            return implode(' / ', $categories_name);
        } else {
            return null;
        }
    }

    public function getTotalAudioSeconds()
    {
        return $this->audios()->sum('audio_length');

    }


    public function doAutoApproveAudios()
    {
        $ordered_audios = $this->ordered_audios;
        $pending_audios = [];

        /** @var Audio $audio */
        foreach ($ordered_audios as $audio) {
            if ($audio->isPool(Audio::POOL_REVIEW) ||
                $audio->isPool(Audio::POOL_UNDETERMINED) ||
                $audio->isStatus(Audio::STATUS_REJECTED)) {
                break;
            } else if ($audio->isPool(Audio::POOL_LARGE) &&
                $audio->isStatus(Audio::STATUS_NOT_REVIEWED)) {
                $pending_audios[] = $audio;
            } else if ($audio->isStatus(Audio::STATUS_APPROVED) && count($pending_audios) > 0) {

                // update status
                foreach ($pending_audios as $pending_audio) {
                    $pending_audio->status = Audio::STATUS_APPROVED;
                    $pending_audio->save();
                }

            }
        }

    }

}
