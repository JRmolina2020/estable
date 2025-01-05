<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminTo = User::create(['name' => 'jose', 'email' => 'admin@gmail.com', 'password' => Hash::make('password')]);
        $role = Role::create(['name' => 'administrador']);
        $permission_array = [];
        array_push($permission_array, Permission::create(['name' => 'enviar factura']));
        array_push($permission_array, Permission::create(['name' => 'eliminar cliente']));
        array_push($permission_array, Permission::create(['name' => 'gestion producto']));
        array_push($permission_array, Permission::create(['name' => 'ver indicador']));
        array_push($permission_array, Permission::create(['name' => 'modificar entrada']));
        array_push($permission_array, Permission::create(['name' => 'eliminar entrada']));
        array_push($permission_array, Permission::create(['name' => 'registrar entrada']));
        array_push($permission_array, Permission::create(['name' => 'seguridad']));
        array_push($permission_array, Permission::create(['name' => 'eliminar factura']));
        $adminTo->assignRole($role->id);
        $role->syncPermissions($permission_array);
    }
}
