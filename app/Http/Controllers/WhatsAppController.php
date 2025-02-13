<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;

class WhatsAppController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'message' => 'required'
        ]);

        $phone = $request->phone;  // Número de destino en formato internacional (+52 para México, +57 para Colombia, etc.)
        $message = $request->message;

        $this->twilioService->sendWhatsAppMessage($phone, $message);

        return response()->json(['success' => 'Mensaje enviado con éxito.']);
    }
}
