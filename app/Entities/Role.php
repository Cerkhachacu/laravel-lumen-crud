<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'last_updated_by'];
    // public $timestamps = false;
    public $storeRules = [
        'name' => 'required|max:255|unique:educations',
        'lastUpdatedBy' => 'required|exists:users,id'
    ];
    public $updateRules = [
        'name' => 'required|max:255|unique:educations',
        'lastUpdatedBy' => 'required|exists:users,id'
    ];
}
