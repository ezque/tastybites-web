<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Closure;

class VerifyCsrfToken extends Middleware
{
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            \Log::error('CSRF Mismatch', [
                'session' => $request->session()->token(),
                'header' => $request->header('X-XSRF-TOKEN'),
                'cookie' => $request->cookie('XSRF-TOKEN'),
            ]);

            throw $e;
        }
    }
}
