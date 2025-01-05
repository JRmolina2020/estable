<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index($status)
    {
        $products = DB::table('products as p')
            ->join('categories as c', 'c.id', '=', 'p.categorie_id')
            ->select(
                'p.id',
                'p.name',
                'p.price',
                'p.price_two',
                'p.cost',
                'p.priceSv',
                'p.price_twoSv',
                'p.type_iva',
                'p.stock',
                'p.status',
                'c.name as type',
                'c.id as idc',
            )
            ->where('p.status', $status)
            ->orderBy('p.stock', 'DESC')->get();
        return $products;
    }

    //LIST PRODUCTS STOCK 0
    public function index2()
    {
        $products = DB::table('products as p')
            ->join('categories as c', 'c.id', '=', 'p.categorie_id')
            ->select(
                'p.id',
                'p.name',
                'p.cost',
                'c.name as type',
            )
            ->orderBy('p.name', 'ASC')
            ->where('p.stock', 0)
            ->where('p.status', 1)
            ->get();
        return $products;
    }
    //producto con más rotación
    public function index_three()
    {
        $products_r = DB::table('factures as f')
            ->join('facture_details as fd', 'f.id', '=', 'fd.facture_id')
            ->join('products as p', 'p.id', '=', 'fd.product_id')
            ->select(
                DB::raw('p.name,SUM(fd.tot) as tot'),
            )
            ->groupBy('p.name')
            ->orderBy('tot', 'DESC')
            ->where('f.status', 1)
            ->where('p.status', 1)
            ->limit(10)
            ->get();
        return $products_r;
    }


    public function store(Request $request)
    {
        Product::create([
            'name' => $request['name'],
            'price' => $request['price'],
            'price_two' => $request['price_two'],
            'cost' => $request['cost'],
            'priceSv' => $request['priceSv'],
            'price_twoSv' => $request['price_twoSv'],
            'type_iva' => $request['type_iva'],
            'vm' => $request['vm'],
            'vd' => $request['vd'],
            'categorie_id' => $request['categorie_id'],
        ]);
        return response()->json(['message' => 'El producto ha sido creado'], 200);
    }

    public function update($id)
    {
        $product = Product::find($id, ['id']);
        $product->fill([
            'name' => request('name'),
            'price' => request('price'),
            'price_two' => request('price_two'),
            'cost' => request('cost'),
            'type_iva' => request('type_iva'),
            'categorie_id' => request('categorie_id'),
            'priceSv' => request('priceSv'),
            'price_twoSv' => request('price_twoSv'),
            'vm' => request('vm'),
            'vd' => request('vd'),

        ])->save();
        return response()->json(['message' => 'El producto ha sido modificado'], 201);
    }
    public function available($id)
    {
        $products = Product::find($id, ['id']);
        $products->status = 1;
        $products->save();
        return response()->json(["message" => "Ha sido activado"]);
    }
    public function locked($id)
    {
        $products = Product::find($id, ['id']);
        $products->status = 0;
        $products->stock = 0;
        $products->save();
        return response()->json(["message" => "Ha sido Bloqueado"]);
    }
}
