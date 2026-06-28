<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Menu yang merepresentasikan grup/header menu pada sidebar.
 */
class Menu extends Model
{
    use HasFactory;

    // Nama tabel menggunakan huruf besar sesuai schema
    protected $table = 'Menu';

    // Field yang dapat diisi secara massal
    protected $fillable = [
        'code', 'name', 'can_expand', 'menu_order',
        'created_date', 'created_by', 'updated_date', 'updated_by',
    ];

    // Nonaktifkan timestamps Eloquent default
    public $timestamps = false;

    /**
     * Relasi: satu menu memiliki banyak sub menu.
     */
    public function subMenus()
    {
        return $this->hasMany(SubMenu::class, 'menu_id')->orderBy('id');
    }
}
