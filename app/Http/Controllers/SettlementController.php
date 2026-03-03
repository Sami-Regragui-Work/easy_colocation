<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{
    public function markPaid(Settlement $settlement)
    {
        $settlement->load('colocation');
        $colocation = $settlement->colocation;
        $user = Auth::user();

        abort_unless(
            $user->id === $settlement->payer_id ||
                $user->id === $colocation->owner_id,
            403,
            'Only payer or owner can mark as paid.'
        );

        if (! $settlement->isPending()) {
            return back()->withErrors(['settlement' => 'Already paid.']);
        }

        $settlement->markAsPaid();

        return back()->with('status', 'Payment recorded.');
    }
}
