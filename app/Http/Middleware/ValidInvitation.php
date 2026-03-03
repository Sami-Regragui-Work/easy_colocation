<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidInvitation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $invitation = $request->route('invitation');

        if (!$invitation->isPending()) {
            abort(404, 'Invitation expired or already used.');
        }

        $request->merge(['invitation' => $invitation]);
        return $next($request);
    }
}
