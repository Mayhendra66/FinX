<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';

    // Tabel hanya memiliki kolom created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'account_id',
        'category_id',
        'type',
        'amount',
        'transaction_date',
        'note',
        'receipt_image',
        'is_installment',
        'installment_id',
        'savings_goal_id',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'is_installment' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'account_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function installment(): BelongsTo
    {
        return $this->belongsTo(Cicilan::class, 'installment_id');
    }

    public function savingsGoal(): BelongsTo
    {
        return $this->belongsTo(SavingGoal::class, 'savings_goal_id');
    }
}