<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = DB::table('permissions')->select('id', 'name')->orderBy('id', 'desc')->get();
        return $permissions;
    }
    public function store(Request $request)
    {
        $permission = Permission::create([
            'name' => $request['name'],

        ]);
        return response()->json(['message' => 'El permiso ha sido creado'], 200);
    }
    public function update( $id)
    {
        $permission = Permission::find($id);
        $permission->fill([
            'name' => request('name'),
        ])->save();
        return response()->json(['message' => 'El permiso ha sido modificado'], 201);
    }
    public function destroy($id)
    {   
        $role = Permission::find($id);
        if (!$role) {
            return response()->json(["message" => "Permiso no encontrado"], 404);
        }
        $role->delete();
        return response()->json(["message" => "Permiso eliminado"]);
    }
}

