<?php

namespace App\Providers;

use App\Repositories\EducationRepositoryInterface;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\EducationRepository;
use Illuminate\Support\ServiceProvider;

/**
* Class RepositoryServiceProvider
* @package App\Providers
*/
class RepositoryServiceProvider extends ServiceProvider
{
   /**
    * Register services.
    *
    * @return void
    */
   public function register()
   {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $repositories = ["\Education", "\User", "\Certification", "\DrivingLicense", "\Experience", "\LastJobPosition", "\Location", "\Role", "\Seniority"];

        foreach($repositories as $repo)
        {
            $this->app->bind("App\Repositories".$repo."RepositoryInterface", "App\Repositories\Eloquent".$repo."Repository");
        }
    //    $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    //    $this->app->bind(EducationRepositoryInterface::class, EducationRepository::class);
   }
}
