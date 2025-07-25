<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;

class ResetPasswordForMultiAuth extends ResetPassword
{
    protected function resetUrl($notifiable)
    {
        $multi_auth_guard = multi_auth_guard();
        $route_name = $multi_auth_guard . '.password.reset';

        return url(route($route_name, [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
