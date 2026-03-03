<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers'     => User::count(),
            'activeUsers'    => User::whereNull('banned_at')->whereNotNull('email_verified_at')->count(),
            'totalColocations' => Colocation::count(),
            'totalExpenses'  => Expense::count(),
            'totalSettlements' => Settlement::count(),
        ]);
    }
}
