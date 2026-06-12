<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggaran extends Model
{
    protected $table = 'anggaran';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'month',
        'year',
        // ✅ 'spent' dihapus dari fillable karena udah jadi accessor
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * ✅ Hitung spent real-time dari transactions
     * Filter: user_id + category_id yang sama (semua periode)
     */
    public function getSpentAttribute(): float
    {
        return Transaction::where('user_id', $this->user_id)
            ->where('category_id', $this->category_id)
            ->where('type', 'expense')
            ->sum('amount');
    }
}