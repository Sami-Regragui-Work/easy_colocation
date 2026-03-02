<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanManageCategories
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $colocation = $request->route('colocation');

        if (!$colocation || Auth::user()->id !== $colocation->owner_id) {
            abort(403, 'Only the owner can manage categories.');
        }

        return $next($request);
    }
}
