<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class LastJobPosition extends Model
{
    protected $table = 'last_job_positions';
    protected $fillable = ['name', 'last_updated_by'];
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
