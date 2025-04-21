<?php

namespace Jmjl161100\RelationUpdater;

use Illuminate\Support\ServiceProvider;
use Jmjl161100\RelationUpdater\Contracts\RelationUpdater;
use Jmjl161100\RelationUpdater\Services\RelationUpdaterService;

class RelationUpdaterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register(): void
    {
        $this->app->bind(RelationUpdater::class, RelationUpdaterService::class);
    }
}
