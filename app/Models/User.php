<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'banned_at',
        'reputation'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'banned_at' => 'datetime'
        ];
    }

    public function colocations()
    {
        return $this->belongsToMany(Colocation::class, 'colocation_members')->withPivot(['role', 'left_at'])->withTimestamps();
    }

    public function ownedColocations()
    {
        return $this->hasMany(Colocation::class, 'owner_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'payer_id');
    }

    public function sentSettlements()
    {
        return $this->hasMany(Settlement::class, 'payer_id');
    }

    public function receivedSettlements()
    {
        return $this->hasMany(Settlement::class, 'receiver_id');
    }

    // not relations (helpers)

    public function activeColocations()
    {
        return $this->colocations()->wherePivot('left_at', null);
    }

    public function whichColocation(): ?Colocation
    {
        return $this->activeColocations()->first();
    }

    public function whichRole(Colocation $colocation): ?string
    {
        return $this->colocations()
            ->where('colocation_id', $colocation->id)
            ->first()?->pivot?->role;
    }

    public function isBanned(): bool
    {
        return !is_null($this->banned_at);
    }

    public function ban(): void
    {
        $this->update(['banned_at' => now()]);
    }

    public function unban(): void
    {
        $this->update(['banned_at' => null]);
    }
}
