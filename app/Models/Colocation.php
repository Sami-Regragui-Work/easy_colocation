<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'owner_id', 'status'];

    protected function casts(): array
    {
        return [
            'status' => 'string'
        ];
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'colocation_members')->withPivot(['role', 'left_at'])->withTimestamps()->orderBy('colocation_members.role', 'desc');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }

    public function expenses()
    {
        return $this->hasManyThrough(Expense::class, Category::class);
    }

    // not relations (helpers)

    public function activeMembers()
    {
        return $this->members()->wherePivot('left_at', null);
    }

    public function pendingSettlements()
    {
        return $this->settlements()->whereNull('paid_at');
    }

    protected static function booted()
    {
        static::deleting(function (Colocation $coloc) {
            $coloc->categories()->delete();
        });
    }

    public function computeBalances(): array
    {
        $members = $this->activeMembers()->with('expenses')->get();

        if ($members->isEmpty()) {
            return [];
        }

        $total = $this->expenses()->sum('amount');
        $share = $total / $members->count();

        $balances = [];

        foreach ($members as $member) {
            $paid = $member->expenses()->whereHas('category', fn($q) => $q->where('colocation_id', $this->id))->sum('amount');

            $balances[$member->id] = $paid - $share;
        }

        return $balances;
    }

    public function generateSettlements(): void
    {
        $balances = $this->computeBalances();

        $debtors = [];
        $creditors = [];

        foreach ($balances as $userId => $balance) {
            if ($balance < 0) {
                $debtors[$userId] = -$balance;
            } elseif ($balance > 0) {
                $creditors[$userId] = $balance;
            }
        }

        $this->settlements()->whereNull('paid_at')->delete();

        foreach ($debtors as $debtorId => $debt) {
            foreach ($creditors as $creditorId => $credit) {
                if ($debt <= 0) {
                    break;
                }
                if ($credit <= 0 || $debtorId === $creditorId) {
                    continue;
                }

                $amount = min($debt, $credit);

                Settlement::create([
                    'colocation_id' => $this->id,
                    'payer_id' => $debtorId,
                    'receiver_id' => $creditorId,
                    'amount' => $amount,
                ]);

                $debt -= $amount;
                $credit -= $amount;

                $creditors[$creditorId] = $credit;
                $debtors[$debtorId] = $debt;
            }
        }
    }
}
