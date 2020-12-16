<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';
    protected $fillable = ['name', 'last_updated_by'];

    // protected $with = ["user:id,role,email"];

    public $storeRules = [
        'name' => 'required|max:255|unique:educations',
        'lastUpdatedBy' => 'required|exists:users,id'
    ];
    public $updateRules = [
        'name' => 'required|max:255|unique:educations',
        'lastUpdatedBy' => 'required|exists:users,id'
    ];

    public function user() {
        return $this->belongsTo('App\Entities\User', 'last_updated_by');
    }
}
