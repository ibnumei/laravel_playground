<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Model User yang mengimplementasikan JWTSubject untuk autentikasi JWT berbasis RBAC.
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    // Nama tabel menggunakan huruf besar sesuai schema
    protected $table = 'User';

    // Field yang dapat diisi secara massal
    protected $fillable = [
        'role_id', 'name', 'email', 'password',
        'last_login', 'status', 'approval_status',
        'created_date', 'created_by', 'updated_date', 'updated_by',
    ];

    // Field yang disembunyikan saat serialisasi
    protected $hidden = ['password'];

    // Nonaktifkan timestamps Eloquent default
    public $timestamps = false;

    /**
     * Mengembalikan identifier unik untuk JWT payload (id user).
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Mengembalikan klaim custom JWT: role name dan daftar menu yang dapat diakses user.
     */
    public function getJWTCustomClaims()
    {
        $role = $this->role;
        return [
            'role' => $role ? $role->name : null,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    /**
     * Relasi: user ini dimiliki oleh satu role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relasi: satu user memiliki banyak todos.
     */
    public function todos()
    {
        return $this->hasMany(Todo::class, 'user_id');
    }
}
