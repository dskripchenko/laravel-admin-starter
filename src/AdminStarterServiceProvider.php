<?php

declare(strict_types=1);

namespace Dskripchenko\LaravelAdminStarter;

use Dskripchenko\LaravelAdmin\Plugin\Concerns\RegistersAdminPlugin;
use Illuminate\Support\ServiceProvider;

final class AdminStarterServiceProvider extends ServiceProvider
{
    use RegistersAdminPlugin;

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/admin-starter.php', 'admin-starter');

        // Registered here rather than in boot(): the translator caches a
        // locale's JSON lines on first use, and anything that translates
        // while the application boots would load them before this provider's
        // boot() could add the path, so it would never be read.
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');

        $this->registerAdminPlugin(AdminStarterPlugin::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/admin-starter.php' => config_path('admin-starter.php'),
        ], 'admin-starter-config');
    }
}
