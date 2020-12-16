<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{

  protected $table = 'opportunities';
  protected $fillable = ['name', 'last_updated_by'];

  public $storeRules = [
    'name' => 'required|max:255|unique:educations',
    'lastUpdatedBy' => 'required|exists:users,id'
];
public $updateRules = [
    'name' => 'required|max:255|unique:educations',
    'lastUpdatedBy' => 'required|exists:users,id'
];

}
