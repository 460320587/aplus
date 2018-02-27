<?php

namespace Someline\Transformers;

use Someline\Models\Foundation\Audio;

/**
 * Class AudioTransformer
 * @package namespace Someline\Transformers;
 */
class AudioTransformer extends BaseTransformer
{

    /**
     * Transform the Audio entity
     * @param Audio $model
     *
     * @return array
     */
    public function transform(Audio $model)
    {
        return [
            'id' => (int) $model->id,

            /* place your other model properties here */

            'created_at' => (string) $model->created_at,
            'updated_at' => (string) $model->updated_at
        ];
    }
}
