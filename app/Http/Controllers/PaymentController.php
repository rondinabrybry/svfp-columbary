<?php

namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Jobs\SendPaymentCompleteEmail;

class PaymentController extends Controller
{
    public function markAsPaid($id)
    {
        $payment = Payment::findOrFail($id);
        $columbarySlot = $payment->columbarySlot;

        $payment->payment_status = 'Paid';
        $payment->price = $columbarySlot->price;
        $payment->save();

        $columbarySlot->update(['status' => 'Sold']);

        // Dispatch the job to send the payment complete email
        SendPaymentCompleteEmail::dispatch($payment);

        // Delete the data from the reservations table
        Reservation::where('columbary_slot_id', $columbarySlot->id)->delete();

        return back()->with('success', 'Payment marked as paid, email sent to the client, and reservation deleted.');
    }
}