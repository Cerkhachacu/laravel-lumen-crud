<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $table = 'majors';
    protected $fillable = ['name', 'last_updated_by'];
}
