<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteToColocationRequest;
use App\Http\Requests\StoreColocationRequest;
use App\Http\Requests\UpdateColocationRequest;
use App\Mail\InvitationEmail;
use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use function Symfony\Component\Clock\now;

class ColocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $colocations = $user->colocations()->with([
            'members' => fn($q) => $q->withPivot('role', 'left_at'),
            'owner'
        ])->get();

        return view('colocations.index', compact('colocations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('can_create_colocation', Colocation::class);

        return view('colocations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreColocationRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->activeColocations()->exists()) {
            return redirect()->route('colocations.index')->with('error', 'You already belong to an active colocation.');
        }

        $validated = $request->validated();

        $colocation = new Colocation($validated);
        $colocation->owner_id = $user->id;
        $colocation->save();

        $colocation->members()->attach($user->id, ['role' => 'owner']);

        return redirect()->route('colocations.show', $colocation)->with('status', 'Colocation created and you are now the owner.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Colocation $colocation)
    {
        /** @var User $user */
        $user = Auth::user();

        Gate::authorize('can_view_colocation', $colocation);

        $colocation->load([
            'members' => fn($q) => $q->withPivot('role', 'left_at')->wherePivot('left_at', null),
            'owner',
            'categories',
            'expenses' => fn($q) => $q->with(['payer', 'category'])
        ]);

        return view('colocations.show', compact('colocation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Colocation $colocation)
    {
        Gate::authorize('can_update_colocation', $colocation);

        return view('colocations.edit', compact('colocation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateColocationRequest $request, Colocation $colocation)
    {
        Gate::authorize('can_update_colocation', $colocation);

        $validated = $request->validated();

        $colocation->fill($validated);
        $colocation->save();

        return redirect()->route('colocations.show', $colocation)->with('status', 'Colocation updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Colocation $colocation)
    {
        Gate::authorize('can_delete_colocation', $colocation);

        if ($colocation->status === 'active') {
            return redirect()->route('colocations.show', $colocation)->withErrors(['status' => 'You must cancel the colocation first.']);
        }

        if ($colocation->activeMembers()->count() > 1) {
            return redirect()->route('colocations.show', $colocation)->withErrors(['members' => 'You must remove all members before cancelling then deleting.']);
        }

        $name = $colocation->name;
        $colocation->delete();

        return redirect()->route('colocations.index')->with('status', "Colocation \"{$name}\" deleted.");
    }

    public function cancel(Colocation $colocation)
    {
        Gate::authorize('can_cancel_colocation', $colocation);

        if ($colocation->status === 'cancelled') {
            return redirect()->route('colocations.show', $colocation)->withErrors(['status' => 'This colocation is already cancelled.']);
        }

        if ($colocation->activeMembers()->count() > 1) {
            return redirect()->route('colocations.show', $colocation)->withErrors(['members' => 'You must remove all members before cancelling.']);
        }

        $colocation->status = 'cancelled';
        $colocation->members()->updateExistingPivot($colocation->owner_id, ['left_at' => now()]);
        $colocation->save();

        return redirect()->route('colocations.index')->with('status', 'Colocation cancelled.');
    }

    public function invite(InviteToColocationRequest $request, Colocation $colocation)
    {
        Gate::authorize('can_invite_member', $colocation);

        $validated = $request->validated();

        $token = Str::random(32);

        $invitation = Invitation::create([
            'token'         => $token,
            'email'         => $validated['email'],
            'colocation_id' => $colocation->id,
        ]);

        Mail::to($validated['email'])->send(new InvitationEmail($invitation));

        return redirect()->route('colocations.show', $colocation)->with('status', 'Invitation sent.');
    }

    public function removeMember(Colocation $colocation, User $member)
    {
        Gate::authorize('can_remove_member', $colocation, $member);

        $colocation->members()->updateExistingPivot($member->id, ['left_at' => now()]);

        // $member->reputation -= 1;

        $member->save();

        return redirect()->route('colocations.show', $colocation)->with('status', "Member {$member->name} removed.");
    }

    public function quit(Request $request, Colocation $colocation)
    {
        /** @var User $user */
        $user = $request->user();

        Gate::authorize('can_quit_colocation', $colocation);

        $colocation->members()->updateExistingPivot($user->id, ['left_at' => now()]);

        // $user->reputation -= 1;
        // $user->reputation += 1;

        $user->save();

        return redirect()->route('colocations.index')->with('status', 'You have quit the colocation.');
    }
}
