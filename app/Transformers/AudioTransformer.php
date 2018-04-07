<?php

namespace Someline\Transformers;

use Someline\Models\Foundation\Audio;

/**
 * Class AudioTransformer
 * @package namespace Someline\Transformers;
 */
class AudioTransformer extends BaseTransformer
{

    protected $availableIncludes = [
        'album', 'reviews',
    ];

    /**
     * Transform the Audio entity
     * @param Audio $model
     *
     * @return array
     */
    public function transform(Audio $model)
    {
        return [
            'audio_id' => (int)$model->audio_id,

            /* place your other model properties here */
            'user_id' => $model->user_id,
            'album_id' => $model->album_id,
            'name' => $model->name,
            'original_name' => $model->original_name,
            'someline_file_id' => $model->someline_file_id,
            'someline_file_url' => $model->getSomelineFileUrl(),
            'audio_length' => $model->audio_length,
            'sequence' => $model->sequence,
            'status' => $model->status,
            'status_text' => $model->status_text,

            'created_at' => (string)$model->created_at,
            'updated_at' => (string)$model->updated_at
        ];
    }

    public function includeAlbum(Audio $model)
    {
        $album = $model->album;
        if ($album) {
            return $this->item($album, new AlbumTransformer());
        }
        return null;
    }


    public function includeReviews(Audio $model)
    {
        $reviews = $model->getSomelineReviews();
        if ($reviews) {
            return $this->collection($reviews, new SomelineReviewTransformer());
        }
        return null;
    }

}
