<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SavingGoal extends Model
{
    protected $table = 'saving_goals';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'account_id',
        'name',
        'target_amount',
        'current_amount',
        'category',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'date',
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
        return $this->hasMany(Transaction::class, 'savings_goal_id');
    }
}