<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminCatalogPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Venues
            'venue-list', 'venue-create', 'venue-edit', 'venue-delete',
            // Event Types
            'event-type-list', 'event-type-create', 'event-type-edit', 'event-type-delete',
            // Artists
            'artist-list', 'artist-create', 'artist-edit', 'artist-delete',
            // Registrations (admin can see all)
            'registration-list',
        ];

        $created = collect($permissions)->map(fn ($name) =>
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web'])
        );

        // Assign all to admin role (id=1) if exists
        $admin = Role::where('id', 1)->orWhere('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo($created);
        }
    }
}
