<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\User;
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
            static function (User $notifiable): string {
                $urlPath = parse_url(
                    route('auth.mark-email-verified', [
                        'id' => $notifiable->getKey(),
                        'hash' => sha1($notifiable->getEmailForVerification()),
                    ]),
                    PHP_URL_PATH
                );
                /** @var string $frontendUrl */
                $frontendUrl = config('app.frontend_url');

                return $frontendUrl . $urlPath;
            }
        );
    }
}
