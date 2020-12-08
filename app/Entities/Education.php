<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';
    protected $fillable = ['name', 'last_updated_by'];
}
