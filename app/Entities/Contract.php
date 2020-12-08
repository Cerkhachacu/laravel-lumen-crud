<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $fillable = ['name', 'last_updated_by'];
}
