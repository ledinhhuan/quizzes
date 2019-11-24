<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\Interfaces\UserRepository::class, \App\Repositories\Eloquents\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\AnswerRepository::class, \App\Repositories\Eloquents\AnswerRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\QuestionRepository::class, \App\Repositories\Eloquents\QuestionRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\TestAnswerRepository::class, \App\Repositories\Eloquents\TestAnswerRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\TestRepository::class, \App\Repositories\Eloquents\TestRepositoryEloquent::class);

        //:end-bindings:
    }
}
