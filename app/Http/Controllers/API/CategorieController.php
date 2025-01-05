<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;
use Illuminate\Support\Facades\DB;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = DB::table('categories')->select('id', 'name')->orderBy('id', 'desc')->get();
        return $categories;
    }
    public function store(Request $request)
    {
        Categorie::create([
            'name' => $request['name'],
        ]);
        return response()->json(['message' => 'Categoria'], 200);
    }
    public function update($id)
    {
        $categorie = Categorie::find($id, ['id']);
        $categorie->fill([
            'name' => request('name'),

        ])->save();
        return response()->json(['message' => 'La categoria ha sido modificada'], 201);
    }
}
