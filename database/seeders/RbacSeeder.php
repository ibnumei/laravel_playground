<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleSubMenu;
use App\Models\SubMenu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder utama untuk mengisi data RBAC:
 * Role, User, Menu, SubMenu, dan mapping RoleSubMenu sesuai schema.sql.
 */
class RbacSeeder extends Seeder
{
    /**
     * Menjalankan seeding seluruh data RBAC ke database.
     */
    public function run(): void
    {
        $now = '2026-06-28 15:40:00';

        // =====================
        // 1. Seed Roles
        // =====================
        $maker   = Role::create(['name' => 'Maker',   'description' => 'Role Maker',   'status' => 'A', 'approval_status' => 'APPROVED', 'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        $checker = Role::create(['name' => 'Checker', 'description' => 'Role Checker', 'status' => 'A', 'approval_status' => 'APPROVED', 'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        $viewer  = Role::create(['name' => 'Viewer',  'description' => 'Role Viewer',  'status' => 'A', 'approval_status' => 'APPROVED', 'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);

        // =====================
        // 2. Seed Users
        // =====================
        User::create(['role_id' => $maker->id,   'name' => 'Admin',   'email' => 'admin@example.com',   'password' => Hash::make('admin123'), 'status' => 'A', 'approval_status' => 'APPROVED', 'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        User::create(['role_id' => $checker->id, 'name' => 'Checker', 'email' => 'checker@example.com', 'password' => Hash::make('admin123'), 'status' => 'A', 'approval_status' => 'APPROVED', 'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        User::create(['role_id' => $viewer->id,  'name' => 'Viewer',  'email' => 'viewer@example.com',  'password' => Hash::make('admin123'), 'status' => 'A', 'approval_status' => 'APPROVED', 'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);

        // =====================
        // 3. Seed Menus
        // =====================
        $menuDashboard = Menu::create(['code' => 'DASHBOARD',                'name' => 'Dashboard',                'can_expand' => false, 'menu_order' => 1, 'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        $menuAdmin     = Menu::create(['code' => 'ADMINISTRATION_MAINTENANCE','name' => 'Administration Maintenance','can_expand' => true,  'menu_order' => 2, 'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        $menuSetting   = Menu::create(['code' => 'SETTING_PARAMETER',         'name' => 'Setting Parameter',        'can_expand' => true,  'menu_order' => 3, 'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);

        // =====================
        // 4. Seed SubMenus
        // =====================
        // id=1: Dashboard
        $smDashboard = SubMenu::create(['menu_id' => $menuDashboard->id, 'code' => 'DASHBOARD',         'name' => 'Dashboard',           'path' => '/dashboard',           'can_view' => true, 'can_modify' => false, 'can_approve' => false, 'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        // id=2: Role (under Administration Maintenance)
        $smRole      = SubMenu::create(['menu_id' => $menuAdmin->id,     'code' => 'ROLE',               'name' => 'Role',                'path' => '/role',                'can_view' => true, 'can_modify' => true,  'can_approve' => true,  'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        // id=3: Sftp Config (under Setting Parameter)
        $smSftp      = SubMenu::create(['menu_id' => $menuSetting->id,   'code' => 'SFTP_CONFIG',        'name' => 'Sftp Config',         'path' => '/config-sftp',         'can_view' => true, 'can_modify' => true,  'can_approve' => true,  'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        // id=4: Business Parameter
        $smBusiness  = SubMenu::create(['menu_id' => $menuSetting->id,   'code' => 'BUSINESS_PARAMETER', 'name' => 'Business Parameter',  'path' => '/business-parameter',  'can_view' => true, 'can_modify' => true,  'can_approve' => true,  'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        // id=5: System Parameter
        $smSystem    = SubMenu::create(['menu_id' => $menuSetting->id,   'code' => 'SYSTEM_PARAMETER',   'name' => 'System Parameter',    'path' => '/system-parameter',    'can_view' => true, 'can_modify' => true,  'can_approve' => true,  'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        // id=6: User (under Administration Maintenance)
        $smUser      = SubMenu::create(['menu_id' => $menuAdmin->id,     'code' => 'USER',               'name' => 'User',                'path' => '/user',                'can_view' => true, 'can_modify' => true,  'can_approve' => true,  'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);
        // id=7: Gl Maintenance (under Administration Maintenance)
        $smGl        = SubMenu::create(['menu_id' => $menuAdmin->id,     'code' => 'GL_MAINTENANCE',     'name' => 'Gl Maintenance',      'path' => '/gl-maintenance',      'can_view' => true, 'can_modify' => true,  'can_approve' => true,  'created_date' => $now, 'created_by' => 'SYSTEM', 'updated_date' => $now, 'updated_by' => 'SYSTEM']);

        // =====================
        // 5. Seed ROLE_SUB_MENU sesuai schema.sql
        // =====================
        $roleSubMenuData = [
            // --- Maker (can_view=true, can_modify=true, can_approve=false) ---
            [$maker->id, $smDashboard->id, true, false, false],
            [$maker->id, $smRole->id,      true, true,  false],
            [$maker->id, $smSftp->id,      true, true,  false],
            [$maker->id, $smBusiness->id,  true, true,  false],
            [$maker->id, $smSystem->id,    true, true,  false],
            [$maker->id, $smUser->id,      true, true,  false],
            [$maker->id, $smGl->id,        true, true,  false],

            // --- Checker (can_view=true, can_modify=true, can_approve=true) ---
            [$checker->id, $smDashboard->id, true, false, false],
            [$checker->id, $smRole->id,      true, true,  true],
            [$checker->id, $smSftp->id,      true, true,  true],
            [$checker->id, $smBusiness->id,  true, true,  true],
            [$checker->id, $smSystem->id,    true, true,  true],
            [$checker->id, $smUser->id,      true, true,  true],
            [$checker->id, $smGl->id,        true, true,  true],

            // --- Viewer (BUSINESS_PARAMETER dan SYSTEM_PARAMETER: can_view=false) ---
            [$viewer->id, $smDashboard->id, true,  false, false],
            [$viewer->id, $smRole->id,      true,  false, false],
            [$viewer->id, $smSftp->id,      true,  false, false],
            [$viewer->id, $smBusiness->id,  false, false, false], // Disembunyikan untuk Viewer
            [$viewer->id, $smSystem->id,    false, false, false], // Disembunyikan untuk Viewer
            [$viewer->id, $smUser->id,      true,  false, false],
            [$viewer->id, $smGl->id,        true,  false, false],
        ];

        foreach ($roleSubMenuData as [$roleId, $subMenuId, $canView, $canModify, $canApprove]) {
            RoleSubMenu::create([
                'role_id'     => $roleId,
                'sub_menu_id' => $subMenuId,
                'can_view'    => $canView,
                'can_modify'  => $canModify,
                'can_approve' => $canApprove,
                'created_date' => $now,
                'created_by'   => 'SYSTEM',
                'updated_date' => $now,
                'updated_by'   => 'SYSTEM',
            ]);
        }
    }
}
