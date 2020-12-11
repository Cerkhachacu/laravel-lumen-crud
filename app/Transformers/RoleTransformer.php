<?php

namespace App\Transformers;

use App\Transformers\Transformer;

class RoleTransformer extends Transformer
{
    public $type = 'role';
    protected $availableIncludes = ['user'];

    public function transform($model)
    {
        return [
            'id' => (int)$model->id,
            'name' => $model->name,
            // 'created_at' => $model->created_at,
            // 'body' => $model->body,
        ];
    }
    public function includeUser($model)
    {
        return $this->item($model->user, new UserTransformer(), 'user');
    }
}


// use App\Entities\Role;
// use App\Transformers\Transformer;
// use League\Fractal\TransformerAbstract;

// class RoleTransformer extends TransformerAbstract
// {
//     public $type = 'role';
//     protected $availableIncludes = ['user'];

//     public function transform(Role $post)
//     {
//         return [
//             'id' => (int)$post->id,
//             'name' => (string)$post->name,
//             // 'body' => $post->body,
//         ];
//     }
//     // public function includeUser($post)
//     // {
//     //     return $this->item($post->user, new UserTransformer(), 'user');
//     // }
// }
