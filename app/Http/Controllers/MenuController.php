<?php

namespace App\Http\Controllers;

use App\Models\RoleSubMenu;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Controller untuk mengambil data hierarki menu berdasarkan role user yang sedang login.
 */
class MenuController extends Controller
{
    /**
     * Mengembalikan daftar menu hierarkis (Menu -> SubMenu) yang dapat diakses oleh user.
     * Dihitung dari relasi ROLE_SUB_MENU dengan can_view = true.
     */
    public function index(Request $request)
    {
        // Ambil user yang sedang login dari JWT token
        $user = JWTAuth::parseToken()->authenticate();
        $user->load('role');

        // Ambil sub menu yang bisa dilihat oleh role user ini
        $permissions = RoleSubMenu::where('role_id', $user->role_id)
            ->where('can_view', true)
            ->with('subMenu.menu')
            ->get();

        // Susun struktur hierarkis Menu -> SubMenu
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
                    'can_expand' => (bool) $menu->can_expand,
                    'menu_order' => $menu->menu_order,
                    'sub_menus'  => [],
                ];
            }

            $menuMap[$menu->id]['sub_menus'][] = [
                'id'          => $subMenu->id,
                'code'        => $subMenu->code,
                'name'        => $subMenu->name,
                'path'        => $subMenu->path,
                'can_view'    => (bool) $perm->can_view,
                'can_modify'  => (bool) $perm->can_modify,
                'can_approve' => (bool) $perm->can_approve,
            ];
        }

        // Urutkan berdasarkan menu_order
        usort($menuMap, fn($a, $b) => $a['menu_order'] <=> $b['menu_order']);

        return response()->json(array_values($menuMap));
    }
}
