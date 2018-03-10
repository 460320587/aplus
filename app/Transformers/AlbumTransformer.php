<?php

namespace Someline\Transformers;

use Someline\Models\Foundation\Album;

/**
 * Class AlbumTransformer
 * @package namespace Someline\Transformers;
 */
class AlbumTransformer extends BaseTransformer
{

    /**
     * Transform the Album entity
     * @param Album $model
     *
     * @return array
     */
    public function transform(Album $model)
    {
        return [
            'album_id' => (int)$model->album_id,

            /* place your other model properties here */
            'user_id' => $model->user_id,
            'book_id' => $model->book_id,
            'name' => $model->name,
            'author' => $model->author,
            'broadcaster' => $model->broadcaster,
            'broadcaster_type' => $model->broadcaster_type,
            'someline_image_id' => $model->someline_image_id,
            'someline_image_url' => $model->getSomelineImageUrl(),
            'thumbnail_image_url' => $model->getSomelineImageUrlForType('thumbnail'),
            'brief' => $model->brief,
            'payment_type' => $model->payment_type,
            'payment_amount' => $model->payment_amount,
            'someline_category_id' => $model->someline_category_id,
            'keywords' => $model->keywords,
            'copyright' => $model->copyright,
            'status' => $model->status,
            'status_text' => $model->status_text,

            'created_at' => (string)$model->created_at,
            'updated_at' => (string)$model->updated_at
        ];

        $isEdit = request()->get('edit', false);
        if($isEdit) {
            $data['keywords_data'] = explode(',', $data['kaywords']);
        }
        return $data;
    }
}
