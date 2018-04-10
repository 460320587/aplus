<?php
namespace Someline\Models\Traits;

use Someline\Models\Foundation\User;

trait RelationRelatedUserTrait
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function related_user()
    {
        return $this->belongsTo(User::class, 'related_user_id', 'user_id');
    }

}