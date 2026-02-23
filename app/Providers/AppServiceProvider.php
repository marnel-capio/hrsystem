<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use App\Events\EmailSent;
use App\Listeners\LogMail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        // 🔹 Register EmailSent listener
        Event::listen(EmailSent::class, [LogMail::class, 'handle']);

        // 🔹 Share global props with Inertia
        Inertia::share([
            'menuPermissions' => Config::get('constants.menuPermissions'),
            'hiddenLinks' => Config::get('constants.hiddenLinks'),
            'auth' => function () {
                $user = Auth::user();
                return $user ? [
                    'id' => $user->id,
                    'permissions' => $user->permissions,
                    'name' => $user->name,
                    // Add any other user info you need globally
                ] : null;
            },
        ]);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        // Use immutable dates
        Date::use(CarbonImmutable::class);

        // Prevent destructive commands in production
        DB::prohibitDestructiveCommands(app()->isProduction());

        // Default password rules
        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}