<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function handle(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ]);
        }

        /**
         * @var User $user
         */

        $user = Auth::user();
        if ($user->isBanned()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Account banned.',
            ]);
        }

        $request->session()->regenerateToken();

        return redirect()->intended(route('colocations.index'));
    }
}
