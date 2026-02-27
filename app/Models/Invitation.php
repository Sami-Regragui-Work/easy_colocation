<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'email', 'colocation_id', 'accepted_at', 'refused_at'];

    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
            'refused_at' => 'datetime'
        ];
    }

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    // not relations (helpers)

    public function isPending(): bool
    {
        return is_null($this->accepted_at) && is_null($this->refused_at);
    }
}
