<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Akun extends Model
{
    protected $table = 'akun';
    
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'account_no',
        'balance',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cicilan(): HasMany
    {
        return $this->hasMany(Cicilan::class, 'account_id');
    }

    public function savingGoals(): HasMany
    {
        return $this->hasMany(SavingGoal::class, 'account_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }
}