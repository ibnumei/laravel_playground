<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model RoleSubMenu yang merepresentasikan mapping hak akses antara Role dan SubMenu.
 */
class RoleSubMenu extends Model
{
    use HasFactory;

    // Nama tabel menggunakan huruf besar sesuai schema
    protected $table = 'ROLE_SUB_MENU';

    // Field yang dapat diisi secara massal
    protected $fillable = [
        'role_id', 'sub_menu_id',
        'can_view', 'can_modify', 'can_approve',
        'created_date', 'created_by', 'updated_date', 'updated_by',
    ];

    // Nonaktifkan timestamps Eloquent default
    public $timestamps = false;

    /**
     * Relasi: mapping ini milik satu role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relasi: mapping ini milik satu sub menu.
     */
    public function subMenu()
    {
        return $this->belongsTo(SubMenu::class, 'sub_menu_id');
    }
}
