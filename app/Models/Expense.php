<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'amount', 'start_at', 'payer_id', 'category_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_at' => 'date',
    ];

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function colocation()
    {
        return $this->through('category')->belongsTo(Colocation::class);
    }
}
