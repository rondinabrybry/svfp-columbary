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
        
        $totalSlots = ColumbarySlot::count();
        $availableSlots = ColumbarySlot::where('status', 'Available')->count();
        $notAvailableSlots = ColumbarySlot::where('status', 'Not Available')->count();
        $reservedSlots = ColumbarySlot::where('status', 'Reserved')->count();
        $soldSlots = ColumbarySlot::where('status', 'Sold')->count();

        $dailySales = ColumbarySlot::whereDate('updated_at', Carbon::today())
            ->where('status', 'Sold')
            ->sum('price');

        $monthlySales = ColumbarySlot::whereMonth('updated_at', Carbon::now()->month)
            ->where('status', 'Sold')
            ->sum('price');

        $allTimeSales = ColumbarySlot::where('status', 'Sold')
            ->sum('price');

        $slotsByFloorAndVault = ColumbarySlot::select('floor_number', 'vault_number')
            ->selectRaw('COUNT(*) as total_slots')
            ->selectRaw('SUM(CASE WHEN status = "Available" THEN 1 ELSE 0 END) as available_slots')
            ->selectRaw('SUM(CASE WHEN status = "Not Available" THEN 1 ELSE 0 END) as notAvailable_slots')
            ->selectRaw('SUM(CASE WHEN status = "Reserved" THEN 1 ELSE 0 END) as reserved_slots')
            ->selectRaw('SUM(CASE WHEN status = "Sold" THEN 1 ELSE 0 END) as sold_slots')
            ->groupBy('floor_number', 'vault_number')
            ->orderBy('floor_number')
            ->orderBy('vault_number')
            ->paginate(5);

        $floorSummary = ColumbarySlot::select('floor_number')
            ->selectRaw('COUNT(*) as total_slots')
            ->selectRaw('SUM(CASE WHEN status = "Available" THEN 1 ELSE 0 END) as available_slots')
            ->selectRaw('SUM(CASE WHEN status = "Not Available" THEN 1 ELSE 0 END) as notAvailable_slots')
            ->selectRaw('SUM(CASE WHEN status = "Reserved" THEN 1 ELSE 0 END) as reserved_slots')
            ->selectRaw('SUM(CASE WHEN status = "Sold" THEN 1 ELSE 0 END) as sold_slots')
            ->groupBy('floor_number')
            ->orderBy('floor_number')
            ->get();

        $totalPayments = Payment::count();
        $paidPayments = Payment::where('payment_status', 'paid')->count();
        $reservedPayments = Payment::where('payment_status', 'reserved')->count();

        $paymentStatusDistribution = Payment::select('payment_status', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_status')
            ->pluck('count', 'payment_status');

        $recentPayments = Payment::with('columbarySlot')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalValueOfSoldSlots = ColumbarySlot::where('status', 'Sold')->sum('price');
        $totalValueOfReservedSlots = ColumbarySlot::where('status', 'Reserved')->sum('price');

        return view('dashboard', [
            'totalSlots' => $totalSlots,
            'availableSlots' => $availableSlots,
            'notAvailableSlots' => $notAvailableSlots,
            'reservedSlots' => $reservedSlots,
            'soldSlots' => $soldSlots,
            'slotsByFloor' => $floorSummary,
            'slotsByFloorAndVault' => $slotsByFloorAndVault,
            'totalPayments' => $totalPayments,
            'paidPayments' => $paidPayments,
            'reservedPayments' => $reservedPayments,
            'paymentStatusDistribution' => $paymentStatusDistribution,
            'recentPayments' => $recentPayments,
            'totalValueOfSoldSlots' => $totalValueOfSoldSlots,
            'totalValueOfReservedSlots' => $totalValueOfReservedSlots,
            'dailySales' => $dailySales,
            'monthlySales' => $monthlySales,
            'allTimeSales' => $allTimeSales,
        ]);
    }

}