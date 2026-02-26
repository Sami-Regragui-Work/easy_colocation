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
}
