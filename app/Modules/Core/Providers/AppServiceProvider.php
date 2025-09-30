<?php

declare(strict_types=1);

namespace App\Modules\Core\Providers;

use App\Modules\User\Actions\Facades\CreateEmailVerificationSignedRoute;
use App\Modules\User\Models\PersonalAccessToken;
use App\Modules\User\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

final class AppServiceProvider extends ServiceProvider
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
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        ResetPassword::createUrlUsing(
            // @codeCoverageIgnoreStart
            // php-code-coverage doesn't find this, but URL successfully changed, so ignoring this area in coverage
            static function (mixed $notifiable, string $token): string {
                /** @var User $notifiable */
                /** @var string $frontendUrl */
                $frontendUrl = config('app.frontend_url');
                $email = $notifiable->getEmailForPasswordReset();

                return "{$frontendUrl}/password-reset/{$token}?email={$email}";
            }
            // @codeCoverageIgnoreEnd
        );

        VerifyEmail::createUrlUsing(
            static function (mixed $notifiable): string {
                /** @var User $notifiable */
                /** @var string $frontendUrl */
                $frontendUrl = config('app.frontend_url');
                $temporarySignedRoute = CreateEmailVerificationSignedRoute::run($notifiable);

                return $frontendUrl . $temporarySignedRoute;
            }
        );
    }
}
