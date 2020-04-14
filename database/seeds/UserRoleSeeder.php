<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'add contest']);
        Permission::create(['name' => 'update contest']);
        Permission::create(['name' => 'delete contest']);
        Permission::create(['name' => 'edit contest']);
        Permission::create(['name' => 'add contestant']);
        Permission::create(['name' => 'update contestant']);
        Permission::create(['name' => 'delete contestant']);
        Permission::create(['name' => 'edit contestant']);


        // or may be done by chaining
        $role = Role::create(['name' => 'super-admin'])
            ->givePermissionTo(Permission::all());

        $user = User::find(1);
        $user->assignRole('super-admin');
        $user->givePermissionTo(Permission::all());
    }
}
