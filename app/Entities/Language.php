<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'language';
    protected $fillable = ['name', 'last_updated_by'];
}
