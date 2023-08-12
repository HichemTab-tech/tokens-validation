<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Laravel\stubs;

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
        $this->app->singleton(TokensValidation::class, function () {
            return TokensValidation::class;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @noinspection PhpUndefinedFunctionInspection
     */
    public function boot(): void
    {
        TokensValidation::setConfig(config('tokensvalidation'));
        TokensValidation::prepare();
        $this->app->singleton(TokensValidation::class, function () {
            return TokensValidation::class;
        });
    }
}