<?php

namespace App\Transformers;

use App\Transformers\Transformer;

class EducationTransformer extends Transformer
{
    public $type = 'education';
    protected $availableIncludes = ['user'];

    public function transform($model)
    {
        return [
            'id' => (int)$model->id,
            'name' => $model->name,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
            'last_updated_by' => (int)$model->last_updated_by,
        ];
    }
    public function includeUser($model)
    {
        return $this->item($model->user, new UserTransformer(), 'user');
    }
}
