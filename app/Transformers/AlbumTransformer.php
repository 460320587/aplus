<?php

namespace Someline\Transformers;

use Someline\Component\Category\Transformers\Traits\SomelineIncludeManyCategories;
use Someline\Models\Foundation\Album;
use Someline\Models\Image\SomelineImage;

/**
 * Class AlbumTransformer
 * @package namespace Someline\Transformers;
 */
class AlbumTransformer extends BaseTransformer
{

    use SomelineIncludeManyCategories;

    protected $availableIncludes = [
        'audios', 'latest_audio', 'categories', 'related_user'
    ];

    /**
     * Transform the Album entity
     * @param Album $model
     *
     * @return array
     */
    public function transform(Album $model)
    {
        $data = [
            'album_id' => (int)$model->album_id,

            /* place your other model properties here */
            'user_id' => $model->user_id,
            'related_user_id' => $model->related_user_id,
            'book_id' => $model->book_id,
            'name' => $model->name,
            'author' => $model->author,
            'category_text' => $model->getCategoryText(),
            'broadcaster' => $model->broadcaster,
            'broadcaster_type' => $model->broadcaster_type,
            'someline_image_urls' => $model->getImageUrls(),
            'brief' => $model->brief,
            'payment_type' => $model->payment_type,
            'payment_type_text' => $model->payment_type_text,
            'payment_amount' => $model->payment_amount,
            'payment_percentage' => $model->payment_percentage,
            'keywords' => $model->keywords,
            'copyright' => $model->copyright,
            'audio_bitrate' => $model->audio_bitrate,
            'audios_count' => $model->audios()->count(),
            'rejected_audios_count' => $model->rejected_audios()->count(),
            'status' => $model->status,
            'status_text' => $model->status_text,

            'created_at' => (string)$model->created_at,
            'updated_at' => (string)$model->updated_at
        ];

        $isEditMode = request()->get('edit', false);
        if ($isEditMode) {
            $data['keywords_data'] = explode(',', $data['keywords']);
            $data['images_data'] = SomelineImage::toImagesData($model->getImages());
            $data['someline_category_ids'] = $model->getSomelineCategories()->pluck('someline_category_id');
        }
        return $data;
    }

    public function includeAudios(Album $model)
    {
        $audios = $model->ordered_audios;
        if (count($audios) > 0) {
            return $this->collection($audios, new AudioTransformer());
        }
        return null;
    }

    public function includeLatestAudio(Album $model)
    {
        $audio = $model->audios()->orderBy('updated_at', 'desc')->first();
        if ($audio) {
            return $this->item($audio, new AudioTransformer());
        }
        return null;
    }

    public function includeRelatedUser(Album $model)
    {
        $audio = $model->getRelatedUser();
        if ($audio) {
            return $this->item($audio, new UserTransformer());
        }
        return null;
    }

}
