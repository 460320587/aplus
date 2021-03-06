<?php

namespace Someline\Transformers;

use Someline\Models\Foundation\User;

/**
 * Class UserTransformer
 * @package namespace Someline\Transformers;
 */
class UserTransformer extends BaseTransformer
{

    /**
     * Transform the User entity
     * @param User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        $role = $model->roles()->first();
        return [
            'user_id' => (int)$model->getUserId(),

            /* place your other model properties here */
            'name' => $model->name,
            'username' => $model->username,
            'email' => $model->email,
            'role' => $role ? $role->name : null,
            'role_text' => $role ? $role->display_name : null,

            'created_at' => (string)$model->created_at,
            'updated_at' => (string)$model->updated_at
        ];
    }
}
