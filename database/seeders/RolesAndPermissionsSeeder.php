<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_dashboard',
            'manage_members',
            'manage_subscriptions',
            'manage_coaches',
            'manage_pt_packages',
            'manage_pt_session_plans',
            'manage_rfid_cards',
            'view_access_logs',
            'manage_settings',
            'manage_wifi_configurations',
            'manage_roles',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $coach = Role::firstOrCreate(['name' => 'coach', 'guard_name' => 'web']);
        $coach->givePermissionTo([
            'view_dashboard',
            'manage_members',
            'manage_pt_packages',
            'manage_pt_session_plans',
            'view_access_logs',
        ]);

        $frontdesk = Role::firstOrCreate(['name' => 'frontdesk', 'guard_name' => 'web']);
        $frontdesk->givePermissionTo([
            'view_dashboard',
            'manage_members',
            'manage_subscriptions',
            'manage_rfid_cards',
            'view_access_logs',
        ]);
    }
}
