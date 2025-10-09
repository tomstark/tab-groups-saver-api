<?php

declare(strict_types=1);

namespace App\Modules\User\HTTP\Enums;

enum AuthRouteNames: string
{
    case Login = 'auth.login';
    case Logout = 'auth.logout';
    case MarkEmailVerified = 'auth.mark-email-verified';
    case Register = 'auth.register';
    case ResetPassword = 'auth.reset-password';
    case SendEmailVerification = 'auth.send-email-verification';
    case SendForgottenPasswordLink = 'auth.send-forgotten-password-link';
}
