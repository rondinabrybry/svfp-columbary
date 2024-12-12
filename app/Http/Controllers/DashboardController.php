<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ColumbarySlot;
use App\Models\Payment;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Columbary Slot Statistics
        $totalSlots = ColumbarySlot::count();
        $availableSlots = ColumbarySlot::where('status', 'Available')->count();
        $notAvailableSlots = ColumbarySlot::where('status', 'Not Available')->count();
        $reservedSlots = ColumbarySlot::where('status', 'Reserved')->count();
        $soldSlots = ColumbarySlot::where('status', 'Sold')->count();

    // Slots by Floor and Vault Statistics with Pagination
    $slotsByFloorAndVault = ColumbarySlot::select('floor_number', 'vault_number')
        ->selectRaw('COUNT(*) as total_slots')
        ->selectRaw('SUM(CASE WHEN status = "Available" THEN 1 ELSE 0 END) as available_slots')
        ->selectRaw('SUM(CASE WHEN status = "Not Available" THEN 1 ELSE 0 END) as notAvailable_slots')
        ->selectRaw('SUM(CASE WHEN status = "Reserved" THEN 1 ELSE 0 END) as reserved_slots')
        ->selectRaw('SUM(CASE WHEN status = "Sold" THEN 1 ELSE 0 END) as sold_slots')
        ->groupBy('floor_number', 'vault_number')
        ->orderBy('floor_number')
        ->orderBy('vault_number')
        ->paginate(5); // Adjust the number to change items per page

        // Floor-level aggregation
        $floorSummary = ColumbarySlot::select('floor_number')
            ->selectRaw('COUNT(*) as total_slots')
            ->selectRaw('SUM(CASE WHEN status = "Available" THEN 1 ELSE 0 END) as available_slots')
            ->selectRaw('SUM(CASE WHEN status = "Not Available" THEN 1 ELSE 0 END) as notAvailable_slots')
            ->selectRaw('SUM(CASE WHEN status = "Reserved" THEN 1 ELSE 0 END) as reserved_slots')
            ->selectRaw('SUM(CASE WHEN status = "Sold" THEN 1 ELSE 0 END) as sold_slots')
            ->groupBy('floor_number')
            ->orderBy('floor_number')
            ->get();

        // Payment Statistics
        $totalPayments = Payment::count();
        $paymentStatusDistribution = Payment::select('payment_status', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_status')
            ->pluck('count', 'payment_status');

        // Recent Payments
        $recentPayments = Payment::with('columbarySlot')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Total Value of Sold Slots
        $totalValueOfSoldSlots = ColumbarySlot::where('status', 'Sold')
            ->sum('price');

        return view('dashboard', [
            'totalSlots' => $totalSlots,
            'availableSlots' => $availableSlots,
            'notAvailableSlots' => $notAvailableSlots,
            'reservedSlots' => $reservedSlots,
            'soldSlots' => $soldSlots,
            'slotsByFloor' => $floorSummary,
            'slotsByFloorAndVault' => $slotsByFloorAndVault,
            'totalPayments' => $totalPayments,
            'paymentStatusDistribution' => $paymentStatusDistribution,
            'recentPayments' => $recentPayments,
            'totalValueOfSoldSlots' => $totalValueOfSoldSlots
        ]);
    }
}