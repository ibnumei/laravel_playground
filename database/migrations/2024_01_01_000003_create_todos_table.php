<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel todos dengan field id, user_id, title, status, is_completed, created_at.
     * Status digunakan untuk Maker-Checker flow: PENDING, APPROVED, REJECTED.
     */
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('User')->onDelete('cascade');
            $table->string('title');
            $table->string('status', 20)->default('PENDING'); // PENDING | APPROVED | REJECTED
            $table->boolean('is_completed')->default(false);
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Menghapus tabel todos.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
