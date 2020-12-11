<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Seniority extends Model
{
    protected $table = 'seniorities';
    protected $fillable = ['name', 'last_updated_by'];
}
