<?php

namespace App\Transformers;

use App\Transformers\Transformer;

class UserTransformer extends Transformer
{
    public $type = 'user';

    // protected $defaultIncludes = ['role'];

    protected $availableIncludes = ['role'];

    /**
     * @param \App\User $post
     * @return array
     */
    public function transform($model)
    {
        return [
            'id' => (int)$model->id,
            'first_name' => $model->first_name,
            'last_name' => $model->last_name,
            'email' => $model->email,
            'role_id' => (int)$model->role_id,
        ];
    }
    public function includeRole($model)
    {
        return $this->item($model->role, new RoleTransformer(), 'role');
    }
}

// use App\Entities\User;
// use App\Transformers\Transformer;
// use App\Transformers\RoleTransformer;

// class UserTransformer extends TransformerAbstract
// {
//     public $type = 'user';

//     protected $availableIncludes = ['role'];

//     /**
//      * @param \App\User $post
//      * @return array
//      */
//     public function transform(User $post)
//     {
//         return [
//             'id' => $post->id,
//             // 'name' => $post->name,
//             'email' => $post->email,
//             'role_id' => $post->role_id,
//         ];
//     }
//     public function includeRole(User $post)
//     {
//         $role = $post->role;
//         return $this->item($role, new RoleTransformer(), 'role');
//     }
// }
