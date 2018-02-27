<?php

namespace Someline\Presenters;

use Someline\Transformers\AlbumTransformer;

/**
 * Class AlbumPresenter
 *
 * @package namespace Someline\Presenters;
 */
class AlbumPresenter extends BasePresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AlbumTransformer();
    }
}
