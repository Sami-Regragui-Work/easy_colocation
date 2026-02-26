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
}
