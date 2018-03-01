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

            'audio_id' => $model->audio_id,

            /* place your other model properties here */
            'user_id' => $model->user_id,
            'name' => $model->name,
            'original_name' => $model->original_name,
            'someline_file_id' => $model->someline_file_id,
            'audio_length' => $model->audio_length,
            'sequence' => $model->sequence,
            'status' => $model->status,

            'created_at' => (string)$model->created_at,
            'updated_at' => (string)$model->updated_at
        ];
    }
}
