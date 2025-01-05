<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\FactureController;
use App\Http\Controllers\API\FactureDetailController;
use App\Http\Controllers\API\CompanyController;

use PDF;
use Illuminate\Support\Facades\Mail;
class EmailFac extends Controller
{

    public function sendMailWithPDF($id)
    {

        $idf=$id;
        $factures = new FactureController();
        $details = new FactureDetailController();
        $companies = new CompanyController();
        $facture=$factures->factureUnique($idf);
        $facture2=$factures->factureUnique($idf);
        $detail=$details->index($idf);
        $company=$companies->index();
        $email= $facture[0]->email;
        $pdf = PDF::loadView('test', array('facture' => $facture,'facture2' => $facture2,'detail'=>$detail,'company'=>$company)
      );
      
        $data["email"] = $email;
        $data["title"] = "Factura electronica de venta";
        $data["body"] = "Factura pos";
        Mail::send('emailHead', $data, function($message)use($data, $pdf) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), "factura.pdf");
        });

       
    }
}
