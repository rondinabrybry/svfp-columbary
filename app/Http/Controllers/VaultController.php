<?php

namespace App\Http\Controllers;

use App\Models\ColumbarySlot;
use Illuminate\Http\Request;

class VaultController extends Controller
{
    public function getVaults($floor)
    {
        $vaults = ColumbarySlot::select(['id', 'slot_number', 'vault_number', 'status', 'price'])
            ->where('floor_number', $floor)
            ->with(['payment:id,columbary_slot_id,buyer_name,payment_status'])
            ->orderBy('vault_number')
            ->orderByRaw('CAST(slot_number AS UNSIGNED)')
            ->get()
            ->groupBy('vault_number');

        $html = view('columbary.partials.vaults', compact('vaults'))->render();

        return response()->json(['html' => $html]);
    }
}
