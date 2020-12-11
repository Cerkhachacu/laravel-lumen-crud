<?php

namespace App\Transformers;

use App\Transformers\Transformer;

class LocationTransformer extends Transformer
{
    public $type = 'location';

    // protected $defaultIncludes = ['user'];

    protected $availableIncludes = ['user'];

    public function transform($model)
    {
        return [
            'id' => (int) $model->id,
            'name' => $model->name,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ];
    }

    public function includeUser($model)
    {
        return $this->item($model->user, new UserTransformer(), 'user');
    }
}
