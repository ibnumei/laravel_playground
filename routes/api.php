<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Endpoint publik: POST /api/auth/login (tanpa middleware auth)
| Endpoint terproteksi: semua endpoint lain memerlukan token JWT valid
*/

// Endpoint login — tidak memerlukan autentikasi
Route::post('/auth/login', [AuthController::class, 'login']);

// Semua endpoint di bawah ini memerlukan JWT yang valid
Route::middleware('auth:api')->group(function () {

    // GET /api/menus — Ambil hierarki menu berdasarkan role user
    Route::get('/menus', [MenuController::class, 'index']);

    // Endpoint Todo — CRUD dengan permission check RBAC
    Route::get('/todos', [TodoController::class, 'index']);       // List semua todo (+ search)
    Route::post('/todos', [TodoController::class, 'store']);      // Tambah todo (Maker only)
    Route::put('/todos/{id}', [TodoController::class, 'update']); // Update todo (Maker only)
    Route::delete('/todos/{id}', [TodoController::class, 'destroy']); // Hapus todo (Maker only)

    // Checker approval flow
    Route::post('/todos/{id}/approve', [TodoController::class, 'approve']); // Approve todo (Checker only)
    Route::post('/todos/{id}/reject', [TodoController::class, 'reject']);   // Reject todo (Checker only)
});
