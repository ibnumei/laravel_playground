<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat semua tabel utama: Role, User, Menu, SUB_MENU, ROLE_SUB_MENU.
     */
    public function up(): void
    {
        // Tabel Role: menyimpan daftar peran (Maker, Checker, Viewer)
        Schema::create('Role', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description', 255)->nullable();
            $table->string('status', 10)->default('A');
            $table->string('approval_status', 50)->default('APPROVED');
            $table->dateTime('created_date')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamps(0);
        });

        // Tabel User: menyimpan data user dengan relasi ke Role
        Schema::create('User', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('Role')->onDelete('restrict');
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->text('password');
            $table->dateTime('last_login')->nullable();
            $table->string('status', 10)->default('A');
            $table->string('approval_status', 100)->default('APPROVED');
            $table->dateTime('created_date')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamps(0);
        });

        // Tabel Menu: menyimpan header menu sidebar
        Schema::create('Menu', function (Blueprint $table) {
            $table->id();
            $table->string('code', 255);
            $table->string('name', 255);
            $table->boolean('can_expand')->default(false);
            $table->integer('menu_order')->default(0);
            $table->dateTime('created_date')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamps(0);
        });

        // Tabel SUB_MENU: menyimpan item menu yang dapat diklik
        Schema::create('SUB_MENU', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('Menu')->onDelete('cascade');
            $table->string('code', 255);
            $table->string('name', 255);
            $table->string('path', 255);
            $table->boolean('can_view')->default(true);
            $table->boolean('can_modify')->default(false);
            $table->boolean('can_approve')->default(false);
            $table->dateTime('created_date')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamps(0);
        });

        // Tabel ROLE_SUB_MENU: mapping izin akses antara Role dan SUB_MENU
        Schema::create('ROLE_SUB_MENU', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('Role')->onDelete('cascade');
            $table->foreignId('sub_menu_id')->constrained('SUB_MENU')->onDelete('cascade');
            $table->boolean('can_view')->default(false);
            $table->boolean('can_modify')->default(false);
            $table->boolean('can_approve')->default(false);
            $table->dateTime('created_date')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamps(0);
        });
    }

    /**
     * Menghapus semua tabel utama dalam urutan terbalik (relasi aman).
     */
    public function down(): void
    {
        Schema::dropIfExists('ROLE_SUB_MENU');
        Schema::dropIfExists('SUB_MENU');
        Schema::dropIfExists('Menu');
        Schema::dropIfExists('User');
        Schema::dropIfExists('Role');
    }
};
