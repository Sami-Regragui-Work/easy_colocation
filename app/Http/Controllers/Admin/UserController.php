<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('colocations')->orderBy('banned_at', 'asc')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function ban(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Cannot ban an admin.');
        }

        $user->update(['banned_at' => now()]);

        return back()->with('status', 'User banned.');
    }

    public function unban(User $user)
    {
        $user->update(['banned_at' => null]);

        return back()->with('status', 'User unbanned.');
    }

    public function edit(User $user)
    {
        return view('profile.edit', compact('user'));
    }

    public function update(User $user, ProfileUpdateRequest $request)
    {
        $validated = $request->validated();

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return back()->with('status', 'Profile updated.');
    }
}
