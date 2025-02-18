<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\MissionRepository;
use App\Repository\Eloquent\RecordRepository;
use App\Repository\Eloquent\StudentRepository;
use App\Repository\MissionRepositoryInterface;
use App\Repository\StudentRepositoryInterface;
use App\Repository\EloquentRepositoryInterface;
use App\Repository\RecordRepositoryInterface;

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
       $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
       $this->app->bind(MissionRepositoryInterface::class, MissionRepository::class);
       $this->app->bind(RecordRepositoryInterface::class, RecordRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
