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
        $publisherPermissions = [
            SomelineRoleService::addPermission('manage-audios', '声音管理'),
        ];
        $reviewerPermissions = array_merge($publisherPermissions, [
            SomelineRoleService::addPermission('review-audios', '声音审核'),
        ]);
        $adminPermissions = [
            SomelineRoleService::addPermission('manage-users', '用户管理'),
            SomelineRoleService::addPermission('manage-albums', '专辑管理'),
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

        $reviewerRole = SomelineRoleService::addRole('reviewer', '审核员');
        $reviewerRole->syncPermissions($reviewerPermissions);

        $publisherRole = SomelineRoleService::addRole('publisher', '发布员');
        $publisherRole->syncPermissions($publisherPermissions);

        // sync user roles
        SomelineRoleService::syncUserRoles(1, [$adminRole]);
        SomelineRoleService::syncUserRoles(2, [$reviewerRole]);
        SomelineRoleService::syncUserRoles(3, [$publisherRole]);
    }
}
