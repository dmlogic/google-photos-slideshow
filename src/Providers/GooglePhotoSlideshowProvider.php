<?php

namespace DmLogic\GooglePhotoSlideshow\Providers;

use Illuminate\Support\ServiceProvider;
use DmLogic\GooglePhotoSlideshow\Commands\IndexPhotos;
use DmLogic\GooglePhotoSlideshow\Commands\ReIndexAlbum;

class GooglePhotoSlideshowProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     */
    public function boot()
    {
        $this->registerCommands();
        $this->registerRoutes();
        $this->setConfigValues();
        $this->registerMigrations();
        $this->registerViews();
    }

    private function registerCommands(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }
        $this->commands([
            IndexPhotos::class,
            ReIndexAlbum::class,
        ]);
    }

    private function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
    }

    private function setConfigValues(): void
    {
        // Allows values to be picked up from .env
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/photos.php',
            'photos'
        );

        // Parse in the oauth creds to the config array
        $creds = json_decode(file_get_contents(__DIR__ . '/../../credentials.json'), true);
        config(['photos.oauth' => $creds['web']]);

        // Create a new storage location based on the path we set at build
        config(['filesystems.disks.photos' => [
            'driver' => 'local',
            'root' => config('photos.storage_path'),
        ]]);
    }

    private function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../views', 'photos');
    }
}
