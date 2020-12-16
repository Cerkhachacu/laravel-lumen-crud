<?php
namespace App\Repositories;

use App\Entities\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
   public function all(): Collection;
}
