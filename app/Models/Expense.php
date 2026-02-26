<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'amount', 'start_at', 'payer_id', 'category_id', 'colocation_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_at' => 'date',
    ];
}
