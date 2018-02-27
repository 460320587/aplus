<?php

namespace Someline\Presenters;

use Someline\Transformers\AudioTransformer;

/**
 * Class AudioPresenter
 *
 * @package namespace Someline\Presenters;
 */
class AudioPresenter extends BasePresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AudioTransformer();
    }
}
