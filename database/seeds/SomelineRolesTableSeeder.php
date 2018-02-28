<?php

use Illuminate\Database\Seeder;
use Someline\Component\Role\SomelineRoleService;
use Someline\Models\Foundation\User;

class SomelineRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // permissions
        $adminPermissions = [
            SomelineRoleService::addPermission('manage-users', '用户管理'),
        ];
        $rootOnlyPermissions = [
            SomelineRoleService::addPermission('manage-roles', '角色管理'),
        ];
        $rootPermissions = array_merge($adminPermissions, $rootOnlyPermissions);

        // roles
        $rootRole = SomelineRoleService::addRole('root', '超级管理员');
        $rootRole->syncPermissions($rootPermissions);

        $adminRole = SomelineRoleService::addRole('admin', '管理员');
        $adminRole->syncPermissions($adminPermissions);

        // sync user roles
        $user = User::find(1);
        SomelineRoleService::syncUserRoles($user, [$rootRole, $adminRole]);
    }
}
