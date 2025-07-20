<?php

namespace Tests\Traits;

trait MultiAuthTestTrait
{
    private function logout(string $guard)
    {
        auth($guard)->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
