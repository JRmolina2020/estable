<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index()
    {
        $company= DB::table('companies')
        ->select('id','nit','name','representative','phone','direction','city','note')
        ->get();
        return $company;
    }
    public function index_two()
    {
        $companies = DB::table('companies as c')
        ->select(
        'id',
        'nit',
        'name',
        'representative', 
        'phone',
        'direction',
        'city',
        'note'
        )->get();
        return $companies;
    }
 
    public function store(Request $request)
    {
         Company::create([
            'user_id' =>auth()->id(),
            'nit' => $request['nit'],
            'name' => $request['name'],
            'representative' => $request['representative'],
            'phone' => $request['phone'],
            'direction' => $request['direction'],
            'city' => $request['city'],
            'note' => $request['note'],
           
        ]);
        return response()->json(['message' => 'Datos de la empresa registrado'], 200);
    }

    public function update(Request $request, $id)
    {
        $company = Company::find($id, ['id']);
        $company->fill([
            'nit' => request('nit'),
            'name' => request('name'),
            'representative' => request('representative'),
            'phone' => request('phone'),
            'direction' => request('direction'),
            'phone' => request('phone'),
            'city' => request('city'),
            'note' => request('note'),
  
        ])->save();
        return response()->json(['message' => 'Información modificada'], 201);
    }
    public function destroy($id)
    {  
        $company = Company::find($id);
        if (!$company) {
            return response()->json(["message" => "Datos no encontrado"], 404);
        }
        $company->delete();
        return response()->json(["message" => "Información eliminada"]);
    }
}
