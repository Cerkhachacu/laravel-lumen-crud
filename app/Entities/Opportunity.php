<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{

  protected $table = 'opportunities';
  protected $fillable = ['name', 'last_updated_by'];

}
