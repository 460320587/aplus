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
            'id' => (int) $model->id,

            /* place your other model properties here */

            'created_at' => (string) $model->created_at,
            'updated_at' => (string) $model->updated_at
        ];
    }
}
