<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment; // Import the Payment model
use App\Models\ColumbarySlot; // Import the ColumbarySlot model

class ClientController extends Controller
{
    public function index()
    {
        // Fetch unique client names and count Paid and Reserved statuses
        $clients = Payment::select('buyer_name')
            ->selectRaw('SUM(CASE WHEN payment_status = "Paid" THEN 1 ELSE 0 END) as paid_count')
            ->selectRaw('SUM(CASE WHEN payment_status = "Reserved" THEN 1 ELSE 0 END) as reserved_count')
            ->groupBy('buyer_name')
            ->get();

        // Fetch details of each reserved and sold slot for each client
        foreach ($clients as $client) {
            $client->reserved_slots = Payment::where('buyer_name', $client->buyer_name)
                ->where('payment_status', 'Reserved')
                ->with('columbarySlot')
                ->get();

            $client->paid_slots = Payment::where('buyer_name', $client->buyer_name)
                ->where('payment_status', 'Paid')
                ->with('columbarySlot')
                ->get();
        }

        // Pass the data to the clients.blade.php view
        return view('clients', ['clients' => $clients]);
    }
}