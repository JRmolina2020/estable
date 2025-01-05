<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provider;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = DB::table('providers')->select('id', 'name', 'company', 'phone')
            ->orderBy('id', 'ASC')->get();
        return $providers;
    }

    public function store(Request $request)
    {
        Provider::create([
            'name' => $request['name'],
            'company' => $request['company'],
            'phone' => $request['phone'],

        ]);
        return response()->json(['message' => 'El proveedor ha sido creado'], 200);
    }
    public function update($id)
    {
        $prodiver = Provider::find($id, ['id']);
        $prodiver->fill([
            'name' => request('name'),
            'phone' => request('phone'),
            'company' => request('company'),

        ])->save();
        return response()->json(['message' => 'El proveedor ha sido modificado'], 201);
    }
}
