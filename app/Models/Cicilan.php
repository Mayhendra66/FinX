<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cicilan extends Model
{
    protected $table = 'cicilan';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'account_id',
        'name',
        'total_amount',
        'monthly_amount',
        'total_months',
        'paid_months',
        'start_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'status' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'account_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'installment_id');
    }
}