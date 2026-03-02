<?php

namespace App\Http\Middleware;

use App\Models\Colocation;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ColocationMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd([
        //     'route_colocation' => request()->route('colocation'),
        //     'colocation_model' => Colocation::find(request()->route('colocation')),
        //     'user_id' => Auth::id(),
        //     'user' => Auth::user(),
        //     'members_collection' => $colocation->members ?? 'NO COLOCATION YET',
        // ]);

        // $colocation = Colocation::findOrFail(request()->route('colocation'));
        $colocationId = $request->route('colocation');

        $colocation = is_numeric($colocationId)
            ? Colocation::find($colocationId)
            : $colocationId;

        if (!$colocation || !$colocation->members()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You are not a member of this colocation.');
        }

        return $next($request);
    }
}
