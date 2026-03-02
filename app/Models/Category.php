<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'colocation_id'];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // not relations (helpers)

    public static function getNameSuggestions(string $query = ''): Collection
    {
        return self::onlyTrashed()->where('name', 'like', "%{$query}%")->select('name')->distinct()->orderBy('name')->limit(10)->pluck('name');
    }
}
