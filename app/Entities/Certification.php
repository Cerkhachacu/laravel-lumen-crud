<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $table = 'certifications';
    protected $fillable = ['name', 'last_updated_by'];

    public function user() {
        return $this->belongsTo('App\Entities\User', 'last_updated_by');
    }
}
