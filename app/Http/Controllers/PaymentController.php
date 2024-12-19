<?php

namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function reserveSlot(Request $request, $id)
    {
        $slot = ColumbarySlot::findOrFail($id);
        if ($slot->status !== 'Available') {
            return back()->with('error', 'Slot is not available.');
        }

        $slot->status = 'Reserved';
        $slot->save();

        Payment::create([
            'columbary_slot_id' => $slot->id,
            'buyer_name' => $request->buyer_name,
            'contact_info' => $request->contact_info,
            'payment_status' => 'Reserved',
        ]);

        return redirect()->route('columbary.index')->with('success', 'Slot reserved successfully.');
    }

    public function markAsPaid($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->payment_status = 'Paid';
        $payment->save();

        $payment->columbarySlot->update(['status' => 'Sold']);

        return back()->with('success', 'Payment marked as paid.');
    }
}
