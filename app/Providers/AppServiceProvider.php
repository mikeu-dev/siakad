<?php

namespace App\Providers;

use App\Models\AuditLog;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureAuditEvents();
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    protected function configureAuditEvents(): void
    {
        Event::listen(Login::class, function (Login $event) {
            AuditLog::create([
                'user_id'    => $event->user->getAuthIdentifier(),
                'aktivitas'  => 'LOGIN',
                'tabel'      => 'users',
                'record_id'  => $event->user->getAuthIdentifier(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                AuditLog::create([
                    'user_id'    => $event->user->getAuthIdentifier(),
                    'aktivitas'  => 'LOGOUT',
                    'tabel'      => 'users',
                    'record_id'  => $event->user->getAuthIdentifier(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'created_at' => now(),
                ]);
            }
        });
    }
}
