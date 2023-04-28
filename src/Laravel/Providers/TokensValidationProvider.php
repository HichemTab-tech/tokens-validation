<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Laravel\Providers;

use HichemtabTech\TokensValidation\TokensValidation;
use Illuminate\Support\ServiceProvider;

class TokensValidationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->publishConfig();
        $this->app->singleton(TokensValidation::class, function () {
            return TokensValidation::class;
        });

        $this->app->make('HichemtabTech\TokensValidation\Laravel\Http\Controllers\InvitationAnswererController')
            ->loadRoutes();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(TokensValidation::class, function () {
            return TokensValidation::class;
        });
    }

    /** @noinspection PhpUndefinedFunctionInspection */
    private function publishConfig(): void
    {
        $configPath = __DIR__.'/../config/tokensvalidation.php';
        $publishPath = config_path('tokensvalidation.php');
        $this->publishes([$configPath => $publishPath], 'config');
        $providerPath = __DIR__.'/../Providers/TokensValidationProvider.php';
        $publishPath2 = app_path('Providers/TokensValidationProvider.php');
        $this->publishes([$providerPath => $publishPath2], 'config');
        $controllerPath = __DIR__.'/../Providers/InvitationAnswererController.php';
        $publishPath3 = app_path('Providers/TokensValidationProvider.php');
        $this->publishes([$controllerPath => $publishPath3], 'controllers');
    }
}