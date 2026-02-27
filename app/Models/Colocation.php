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

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
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
}
