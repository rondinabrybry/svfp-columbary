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
            return response()->json(['error' => 'Slot is not available.'], 400);
        }

        try {
            // Start transaction
            \DB::beginTransaction();

            // Update slot status
            $slot->status = 'Reserved';
            $slot->save();

            // Create payment record
            Payment::create([
                'columbary_slot_id' => $slot->id,
                'buyer_name' => $request->buyer_name,
                'buyer_address' => $request->buyer_address,
                'buyer_email' => $request->buyer_email,
                'contact_info' => $request->contact_info,
                'payment_status' => 'Reserved',
            ]);

            // Commit transaction
            \DB::commit();

            return response()->json(['success' => 'Slot reserved successfully.']);
        } catch (\Exception $e) {
            // Rollback transaction
            \DB::rollBack();

            return response()->json(['error' => 'An error occurred while reserving the slot. Please try again.'], 500);
        }
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