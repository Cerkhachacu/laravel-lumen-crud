<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class DrivingLicense extends Model
{

  protected $table = 'driving_licenses';
  protected $fillable = ['name', 'last_updated_by'];
  public function user()
  {
      return $this->belongsTo('App\Entities\User', 'last_updated_by');
  }

}
