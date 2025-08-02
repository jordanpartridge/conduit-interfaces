<?php

declare(strict_types=1);

namespace JordanPartridge\ConduitInterfaces;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JordanPartridge\ConduitInterfaces\Commands\InitCommand;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InitCommand::class
            ]);
        }
    }
}