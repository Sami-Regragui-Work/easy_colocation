<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptInvitationRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\InviteToColocationRequest;
use App\Mail\InvitationEmail;
use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use \App\Http\Controllers\Auth\LoginController;
use \App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\InvitationAuthRequest;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Colocation $colocation)
    {
        Gate::authorize('can_invite_member', $colocation);

        $invitations = $colocation->invitations()->with(['colocation'])->latest()->limit(10)->get();

        return view('invitations.index', compact('colocation', 'invitations'));
    }

    public function invite(InviteToColocationRequest $request, Colocation $colocation)
    {
        Gate::authorize('can_invite_member', $colocation);

        $validated = $request->validated();
        $token = Str::random(32);

        $invitation = Invitation::create([
            'token' => $token,
            'email' => $validated['email'],
            'colocation_id' => $colocation->id,
        ]);

        $inviteLink = route('invitations.accept', $invitation);

        Mail::to($validated['email'])->send(new InvitationEmail($invitation));

        return redirect()->route('colocations.show', $colocation)->with('status', "Invitation sent! : {$inviteLink}");
    }

    public function accept(Invitation $invitation)
    {
        if (!$invitation->isPending()) {
            return redirect()->route('register')->with('error', 'This invitation is no longer valid.');
        }

        return view('invitations.accept', compact('invitation'));
    }

    public function process(InvitationAuthRequest $request, Invitation $invitation)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();

        if ($validated['_action'] === 'register') {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        }

        Auth::login($user);

        $colocation = $invitation->colocation;
        if (!$colocation->members()->where('user_id', $user->id)->exists()) {
            $colocation->members()->attach($user->id, ['role' => 'member']);
        }

        $invitation->update(['accepted_at' => now()]);

        $colocation->generateSettlements();

        return redirect()->route('colocations.show', $colocation)->with('status', 'Welcome to the colocation!');
    }

    public function refuse(Invitation $invitation)
    {
        // if (!$invitation->isPending()) {
        //     return redirect()->route('login')->with('error', 'Invitation already processed.');
        // }

        $invitation->update(['refused_at' => now()]);

        return redirect()->route('login')->with('status', 'Invitation declined.');
    }
}
