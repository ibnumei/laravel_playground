<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Role yang merepresentasikan peran pengguna (Maker, Checker, Viewer).
 */
class Role extends Model
{
    use HasFactory;

    // Nama tabel menggunakan huruf besar sesuai schema
    protected $table = 'Role';

    // Field yang dapat diisi secara massal
    protected $fillable = ['name', 'description', 'status', 'approval_status', 'created_date', 'created_by', 'updated_date', 'updated_by'];

    // Nonaktifkan timestamps Eloquent default (kita gunakan created_date/updated_date)
    public $timestamps = false;

    /**
     * Relasi: satu role dimiliki oleh banyak user.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * Relasi: satu role memiliki banyak mapping role_sub_menu.
     */
    public function roleSubMenus()
    {
        return $this->hasMany(RoleSubMenu::class, 'role_id');
    }
}
