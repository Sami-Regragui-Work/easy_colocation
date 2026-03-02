<?php

namespace App\Http\Middleware;

use App\Models\Colocation;
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
        $colocationId = $request->route('colocation');

        $colocation = is_numeric($colocationId)
            ? Colocation::find($colocationId)
            : $colocationId;

        if (!$colocation || Auth::user()->id !== $colocation->owner_id) {
            abort(403, 'Only the owner can manage categories.');
        }

        return $next($request);
    }
}
