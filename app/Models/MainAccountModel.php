<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MainAccountModel extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit karena menggunakan tanda hubung (-)
    protected $table = 'main-account';

    // Kolom yang diizinkan untuk mass-assignment
    protected $fillable = [
        'user_id',
        'name',
        'account_no',
        'balance',
    ];

    // Nonaktifkan updated_at karena migrasi hanya menyediakan created_at
    const UPDATED_AT = null;

    /**
     * Relasi ke model User (Inverse dari HasMany/HasOne)
     * Setiap akun utama dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}