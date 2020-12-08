<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Remote extends Model
{
    protected $table = 'remotes';
    protected $fillable = ['name', 'last_updated_by'];
}
