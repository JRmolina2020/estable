<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{

    public function index()
    {
        $clients = DB::table('clients')
            ->select('id', 'nit', 'fullname', 'phone', 'email')
            ->orderBy('id', 'desc')->get();
        return $clients;
    }

    public function store(Request $request)
    {
        Client::create([
            'nit' => $request['nit'],
            'fullname' => $request['fullname'],
            'phone' => $request['phone'],
            'email' => $request['email'],

        ]);
        return response()->json(['message' => 'Cliente registrado'], 200);
    }

    public function update($id)
    {
        $client = Client::find($id, ['id']);
        $client->fill([
            'nit' => request('nit'),
            'fullname' => request('fullname'),
            'phone' => request('phone'),
            'email' => request('email'),

        ])->save();
        return response()->json(['message' => 'El cliente ha sido modificado'], 201);
    }
    public function destroy($id)
    {
        $client = Client::find($id);
        if (!$client) {
            return response()->json(["message" => "Cliente no encontrado"], 404);
        }
        $client->delete();
        return response()->json(["message" => "Cliente eliminado"]);
    }
}
