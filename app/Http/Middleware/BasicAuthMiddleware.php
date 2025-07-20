<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('production')) {
            $username = $request->getUser();
            $password = $request->getPassword();
            if ($username === 'wamoreadmin' && $password === 'wamorePaAs0526') {
                return $next($request);
            }
            abort(401, '認証が必要です', [
                header('WWW-Authenticate: Basic realm="Restricted Area"'),
                header('Content-Type: text/plain; charset=utf-8')
            ]);
        }
        return $next($request);
    }
}
