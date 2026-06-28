<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\RoleSubMenu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Controller untuk menangani autentikasi user menggunakan JWT dengan RBAC.
 */
class AuthController extends Controller
{
    /**
     * Login user menggunakan email dan password.
     * Mengembalikan JWT token beserta data user, role, dan struktur menu yang dapat diakses.
     */
    public function login(Request $request)
    {
        // Validasi input email dan password
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan email dan pastikan status aktif
        $user = User::where('email', $request->email)
                    ->where('status', 'A')
                    ->first();

        // Periksa kecocokan password dengan hash yang tersimpan
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // Muat relasi role
        $user->load('role');

        // Buat JWT token untuk user yang berhasil login
        $token = JWTAuth::fromUser($user);

        // Ambil struktur menu hierarkis berdasarkan role user
        $menus = $this->buildMenuStructure($user->role_id);

        // Update waktu login terakhir
        $user->last_login = now();
        $user->save();

        return response()->json([
            'message' => 'Login berhasil.',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role ? $user->role->name : null,
            ],
            'menus' => $menus,
        ]);
    }

    /**
     * Membangun struktur hierarki menu (Menu -> SubMenu) berdasarkan hak akses role.
     * Hanya mengembalikan sub menu dengan can_view = true untuk role tersebut.
     */
    private function buildMenuStructure(int $roleId): array
    {
        // Ambil semua sub menu yang bisa dilihat oleh role ini
        $permissions = RoleSubMenu::where('role_id', $roleId)
            ->where('can_view', true)
            ->with('subMenu.menu')
            ->get();

        // Buat mapping menu_id -> menu data dengan sub menu-nya
        $menuMap = [];

        foreach ($permissions as $perm) {
            $subMenu = $perm->subMenu;
            if (!$subMenu) continue;

            $menu = $subMenu->menu;
            if (!$menu) continue;

            if (!isset($menuMap[$menu->id])) {
                $menuMap[$menu->id] = [
                    'id'         => $menu->id,
                    'code'       => $menu->code,
                    'name'       => $menu->name,
                    'can_expand' => $menu->can_expand,
                    'menu_order' => $menu->menu_order,
                    'sub_menus'  => [],
                ];
            }

            $menuMap[$menu->id]['sub_menus'][] = [
                'id'          => $subMenu->id,
                'code'        => $subMenu->code,
                'name'        => $subMenu->name,
                'path'        => $subMenu->path,
                'can_view'    => $perm->can_view,
                'can_modify'  => $perm->can_modify,
                'can_approve' => $perm->can_approve,
            ];
        }

        // Urutkan menu berdasarkan menu_order
        usort($menuMap, fn($a, $b) => $a['menu_order'] <=> $b['menu_order']);

        return array_values($menuMap);
    }
}
