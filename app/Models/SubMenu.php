<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model SubMenu yang merepresentasikan item menu yang dapat diklik pada sidebar.
 */
class SubMenu extends Model
{
    use HasFactory;

    // Nama tabel menggunakan huruf besar sesuai schema
    protected $table = 'SUB_MENU';

    // Field yang dapat diisi secara massal
    protected $fillable = [
        'menu_id', 'code', 'name', 'path',
        'can_view', 'can_modify', 'can_approve',
        'created_date', 'created_by', 'updated_date', 'updated_by',
    ];

    // Nonaktifkan timestamps Eloquent default
    public $timestamps = false;

    /**
     * Relasi: sub menu ini berada di bawah satu menu induk.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Relasi: sub menu ini memiliki banyak mapping role_sub_menu.
     */
    public function roleSubMenus()
    {
        return $this->hasMany(RoleSubMenu::class, 'sub_menu_id');
    }
}
