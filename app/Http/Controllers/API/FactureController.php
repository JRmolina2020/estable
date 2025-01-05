<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\FactureDetail;
use Carbon\Carbon;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;


class FactureController extends Controller
{
    public function store(Request $request)
    {
        try {
            $mytime = Carbon::now('America/Bogota');
            DB::beginTransaction();
            $facture = new Facture();
            $facture->client_id = $request->client_id;
            $facture->date_facture = $mytime->toDateString();
            $facture->sub = $request->sub;
            $facture->disc = $request->disc;
            $facture->tot = $request->tot;
            $facture->efecty = $request->efecty;
            $facture->other = $request->other;
            $facture->note = $request->note;
            $facture->status = $request->status;
            $facture->type_sale = $request->type_sale;
            $facture->user_id = $request->user;
            $facture->save();
            $details = $request->dataDetails;
            foreach ($details as $ep => $det) {
                $details = new FactureDetail();
                $details->facture_id = $facture->id;
                $details->product_id = $det['product_id'];
                $details->quantity = $det['quantity'];
                $details->price = $det['price'];
                $details->sub = $det['sub'];
                $details->disc = $det['disc'];
                $details->tot = $det['tot'];
                $details->save();
                $id = $details->id;
                $this->update_stock($id, 'add');
            }
            DB::commit();

            return response()->json(['message' => 'Venta registrada'], 200);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function index($date)
    {
        $facture = DB::table('factures as f')
            ->join('clients as c', 'c.id', '=', 'f.client_id')
            ->join('users as u', 'u.id', '=', 'f.user_id')
            ->select(
                'f.id',
                'f.date_facture',
                'f.tot',
                'f.efecty',
                'f.other',
                'f.note',
                'f.status',
                'f.type_sale',
                'u.name',
                'u.id as idu',
                'c.nit',
                'c.fullname',
                'c.id as idc'
            )
            ->where('f.date_facture', $date)
            ->where('f.status', '=', '1')
            ->orderBy('f.id', 'desc')->get();
        return $facture;
    }
    public function index_state($date, $datetwo)
    {
        $facture = DB::table('factures as f')
            ->join('clients as c', 'c.id', '=', 'f.client_id')
            ->join('users as u', 'u.id', '=', 'f.user_id')
            ->select(
                'f.id',
                'f.date_facture',
                'f.tot',
                'f.efecty',
                'f.other',
                'f.note',
                'f.status',
                'f.type_sale',
                'u.name',
            )
            ->whereBetween('f.date_facture', [$date, $datetwo])
            ->where('f.status', '=', '0')
            ->orderBy('f.id', 'desc')->get();
        return $facture;
    }
    public function factureUnique($id)
    {
        $facture = DB::table('factures as f')
            ->join('clients as c', 'c.id', '=', 'f.client_id')
            ->select(
                'f.id',
                'f.date_facture',
                'f.sub',
                'f.disc',
                'f.tot',
                'f.note',
                'f.status',
                'f.type_sale',
                'c.nit',
                'c.fullname',
                'c.phone',
                'c.email',
                'f.created_at'

            )
            ->where('f.id', '=', $id)
            ->orderBy('f.id', 'desc')->get();
        return $facture;
    }

    public function update_stock($id, $type)
    {
        $detail = FactureDetail::find($id);
        $product = Product::find($detail->product_id);
        if ($type == 'add') {
            $stock = DB::raw("stock - $detail->quantity");
        } else {
            $stock = DB::raw("stock + $detail->quantity");
        }
        $product->stock = $stock;
        $product->save();
    }
    public function type_sale($date)
    {
        $facture_tot = DB::table('factures')
            ->select(
                DB::raw('SUM(tot) as tot'),
                DB::raw('SUM(efecty) as efecty'),
                DB::raw('SUM(other) as other'),
            )->where('date_facture', $date)
            ->where('status', 1)
            ->get();
        return $facture_tot;
    }

    public function type_sale_one($date, $type)
    {
        $facture_tot = DB::table('factures')
            ->select(
                DB::raw('SUM(other) as other'),
            )
            ->where('date_facture', $date)
            ->where('type_sale', $type)
            ->where('status', 1)
            ->get();
        return $facture_tot;
    }
    public function clientTot($id)
    {
        $clientot = DB::table('factures as f')
            ->join('clients as c', 'f.client_id', '=', 'c.id')
            ->join('users as u', 'f.user_id', '=', 'u.id')
            ->select('c.fullname as name', 'f.date_facture as date', 'f.tot', 'u.name as user')
            ->where('c.id', $id)
            ->orderBy('f.date_facture', 'DESC')
            ->limit('5')
            ->get();

        return $clientot;
    }
    public function gain($date, $datetwo, $type)
    {
        $gain = DB::table('facture_details as fd')
            ->join('factures as f', 'f.id', '=', 'fd.facture_id')
            ->join('products as p', 'p.id', '=', 'fd.product_id')
            ->select(
                'p.name',
                'p.id',
                DB::raw('SUM(fd.quantity) as quantity'),
                DB::raw('SUM(fd.tot) as tot'),
                DB::raw('p.cost as cost'),
                DB::raw('SUM(fd.tot)-SUM(p.cost*fd.quantity) as gain'),
            )
            ->groupBy('p.name', 'p.cost', 'p.id')
            ->whereBetween('f.date_facture', [$date, $datetwo])
            ->where('f.status', 1)
            ->where('p.categorie_id', $type)
            ->get();
        return $gain;
    }
    //venta total por categoria
    public function gainTot($date, $datetwo, $type)
    {

        $gain_tot = DB::table('facture_details as fd')
            ->join('factures as f', 'f.id', '=', 'fd.facture_id')
            ->join('products as p', 'p.id', '=', 'fd.product_id')
            ->select(
                DB::raw('SUM(fd.tot) as gaintot'),
            )
            ->whereBetween('f.date_facture', [$date, $datetwo])
            ->where('f.status', 1)
            ->where('p.categorie_id', $type)

            ->get();
        return $gain_tot;
    }
    //venta total
    public function gainTotg($date, $datetwo)
    {

        $gain_tot = DB::table('facture_details as fd')
            ->join('factures as f', 'f.id', '=', 'fd.facture_id')
            ->join('products as p', 'p.id', '=', 'fd.product_id')
            ->select(
                DB::raw('SUM(fd.tot) as gaintot'),
            )
            ->whereBetween('f.date_facture', [$date, $datetwo])
            ->where('f.status', 1)
            ->get();
        return $gain_tot;
    }


    //ganancia total por categoria
    public function gainTotf($date, $datetwo, $type)
    {

        $gain_tot = DB::table('facture_details as fd')
            ->join('factures as f', 'f.id', '=', 'fd.facture_id')
            ->join('products as p', 'p.id', '=', 'fd.product_id')
            ->select(
                DB::raw('SUM(fd.tot)-SUM(p.cost*fd.quantity) as gain'),
            )
            ->whereBetween('f.date_facture', [$date, $datetwo])
            ->where('f.status', 1)
            ->where('p.status', 1)
            ->where('p.categorie_id', $type)
            ->get();
        return $gain_tot;
    }
    //ganancia total
    public function gainTotfg($date, $datetwo)
    {

        $gain_tot = DB::table('facture_details as fd')
            ->join('factures as f', 'f.id', '=', 'fd.facture_id')
            ->join('products as p', 'p.id', '=', 'fd.product_id')
            ->select(
                DB::raw('SUM(fd.tot)-SUM(p.cost*fd.quantity) as gain'),
            )
            ->whereBetween('f.date_facture', [$date, $datetwo])
            ->where('f.status', 1)
            ->where('p.status', 1)
            ->get();
        return $gain_tot;
    }
    //sum inventario product cost
    public function Totcost()
    {

        $tot = DB::table('products as p')
            ->select(
                DB::raw('SUM(p.stock) as stock,SUM(p.cost*p.stock) as cost'),
            )
            ->where('p.stock', '>', 0)
            ->where('p.status', 1)
            ->get();
        return $tot;
    }
    public function userTot($date, $datetwo)
    {
        $user_tot = DB::table('factures as f')
            ->join('users as u', 'f.user_id', '=', 'u.id')
            ->select(
                DB::raw('u.name,SUM(f.tot) as tot'),
            )
            ->groupBy('u.name')
            ->where('f.status', 1)
            ->whereBetween('f.date_facture', [$date, $datetwo])
            ->get();
        return $user_tot;
    }

    public function updateStatus($id)
    {
        $facture = Facture::findOrFail($id, ['id']);
        $facture->status = '1';
        $facture->save();
        return response()->json(["message" => "El estado ha cambiado a pagado"]);
    }
    public function destroy($id)
    {
        $facture = Facture::find($id);
        $details = FactureDetail::where('facture_id', $id)->get();
        foreach ($details as $items) {
            $this->update_stock($items->id, 'delete');
        }
        $facture->delete();
        return response()->json(["message" => "Factura eliminada"]);
    }
    public function fupdate($id)
    {
        $facture = Facture::find($id, ['id']);
        $facture->fill([
            'other' => request('other'),
            'efecty' => request('efecty'),
            'type_sale' => request('type_sale'),
            'user_id' => request('user_id'),
            'client_id' => request('client_id'),

        ])->save();
        return response()->json(['message' => 'La factura ha sido modificada'], 201);
    }
}
