<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Todo yang merepresentasikan tugas dalam sistem Maker-Checker.
 * Status: PENDING (diajukan Maker) -> APPROVED/REJECTED (oleh Checker).
 */
class Todo extends Model
{
    use HasFactory;

    // Field yang dapat diisi secara massal
    protected $fillable = ['user_id', 'title', 'status', 'is_completed'];

    // Cast tipe data agar is_completed selalu boolean
    protected $casts = [
        'is_completed' => 'boolean',
    ];

    // Tidak menggunakan updated_at
    const UPDATED_AT = null;

    /**
     * Relasi: todo ini dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
