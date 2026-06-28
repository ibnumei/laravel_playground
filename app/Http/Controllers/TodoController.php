<?php

namespace App\Http\Controllers;

use App\Models\RoleSubMenu;
use App\Models\Todo;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Controller untuk mengelola operasi Todo dalam sistem Maker-Checker berbasis RBAC.
 */
class TodoController extends Controller
{
    /**
     * Mengambil permission dari ROLE_SUB_MENU untuk sub menu Dashboard milik user yang login.
     * Dashboard sub menu digunakan sebagai representasi permission untuk aksi Todo.
     */
    private function getUserPermission()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->load('role');

        // Ambil permission dari sub menu DASHBOARD (id=1) untuk role user ini
        // sebagai proxy permission untuk Todo operations
        $perm = RoleSubMenu::where('role_id', $user->role_id)
            ->join('SUB_MENU', 'ROLE_SUB_MENU.sub_menu_id', '=', 'SUB_MENU.id')
            ->where('SUB_MENU.code', 'DASHBOARD')
            ->select('ROLE_SUB_MENU.*')
            ->first();

        return [$user, $perm];
    }

    /**
     * Mengembalikan semua Todo yang ada (semua role bisa melihat).
     * Mendukung filter pencarian berdasarkan title via query ?search=.
     */
    public function index(Request $request)
    {
        [$user, $perm] = $this->getUserPermission();

        // Bangun query todos — semua role bisa lihat semua todos
        $query = Todo::with('user:id,name,email');

        if ($request->has('search') && $request->search !== '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $todos = $query->orderBy('created_at', 'desc')->get();

        return response()->json($todos);
    }

    /**
     * Menyimpan Todo baru (hanya Maker yang memiliki can_modify = true).
     * Status awal todo adalah PENDING menunggu approval dari Checker.
     */
    public function store(Request $request)
    {
        [$user, $perm] = $this->getUserPermission();

        // Blokir jika user tidak memiliki hak modify
        if (!$perm || !$perm->can_modify) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menambah todo.'], 403);
        }

        // Validasi input: title wajib diisi
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Buat todo baru dengan status PENDING
        $todo = Todo::create([
            'user_id'      => $user->id,
            'title'        => $request->title,
            'status'       => 'PENDING',
            'is_completed' => false,
        ]);

        return response()->json($todo->load('user:id,name,email'), 201);
    }

    /**
     * Menyetujui Todo yang berstatus PENDING (hanya Checker yang memiliki can_approve = true).
     * Status todo berubah menjadi APPROVED.
     */
    public function approve($id)
    {
        [$user, $perm] = $this->getUserPermission();

        // Blokir jika user tidak memiliki hak approve
        if (!$perm || !$perm->can_approve) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menyetujui todo.'], 403);
        }

        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo tidak ditemukan.'], 404);
        }

        if ($todo->status !== 'PENDING') {
            return response()->json(['message' => 'Todo ini tidak dalam status PENDING.'], 422);
        }

        $todo->status = 'APPROVED';
        $todo->save();

        return response()->json($todo->load('user:id,name,email'));
    }

    /**
     * Menolak Todo yang berstatus PENDING (hanya Checker yang memiliki can_approve = true).
     * Status todo berubah menjadi REJECTED.
     */
    public function reject($id)
    {
        [$user, $perm] = $this->getUserPermission();

        // Blokir jika user tidak memiliki hak approve
        if (!$perm || !$perm->can_approve) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menolak todo.'], 403);
        }

        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo tidak ditemukan.'], 404);
        }

        if ($todo->status !== 'PENDING') {
            return response()->json(['message' => 'Todo ini tidak dalam status PENDING.'], 422);
        }

        $todo->status = 'REJECTED';
        $todo->save();

        return response()->json($todo->load('user:id,name,email'));
    }

    /**
     * Mengupdate todo (hanya Maker yang memiliki can_modify = true).
     */
    public function update(Request $request, $id)
    {
        [$user, $perm] = $this->getUserPermission();

        // Blokir jika user tidak memiliki hak modify
        if (!$perm || !$perm->can_modify) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk mengubah todo.'], 403);
        }

        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo tidak ditemukan.'], 404);
        }

        // Validasi input update
        $request->validate([
            'title'        => 'sometimes|required|string|max:255',
            'is_completed' => 'sometimes|boolean',
        ]);

        $todo->update($request->only(['title', 'is_completed']));

        return response()->json($todo->load('user:id,name,email'));
    }

    /**
     * Menghapus Todo (hanya Maker yang memiliki can_modify = true).
     */
    public function destroy($id)
    {
        [$user, $perm] = $this->getUserPermission();

        // Blokir jika user tidak memiliki hak modify
        if (!$perm || !$perm->can_modify) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menghapus todo.'], 403);
        }

        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo tidak ditemukan.'], 404);
        }

        $todo->delete();

        return response()->json(['message' => 'Todo berhasil dihapus.']);
    }
}
