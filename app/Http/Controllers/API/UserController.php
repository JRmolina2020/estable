<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // all users
    public function index()
    {
        $users = User::with('roles:id,name')
            ->orderBy('id', 'desc')
            ->where('status', '1')
            ->get();
        return response()->json($users);
    }

    // add users
    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt('password')

        ]);
        $user->assignRole($request['rol']);
        return response()->json(['message' => 'El usuario ha sido creado'], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->fill([
            'name' => request('name'),
            'email' => request('email'),
        ])->save();
        $user->syncRoles($request['rol']);
        return response()->json(['message' => 'El usuario ha sido modificado'], 201);
    }
    public function available($id)
    {
        $users = User::findOrFail($id, ['id']);
        $users->status = '1';
        $users->save();
        return response()->json(["message" => "Ha sido activado"]);
    }

    public function locked($id)
    {
        $users = User::findOrFail($id, ['id']);
        $users->status = '0';
        $users->save();
        return response()->json(["message" => "Ha sido Bloqueado"]);
    }

    public function updatePassword($id)
    {
        $user = User::find($id, ['id']);
        $user->fill([
            'password' => bcrypt(request('password')),
        ])->save();

        return response()->json(['message' => 'El password ha sido cambiado'], 201);
    }
}
