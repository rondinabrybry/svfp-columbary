<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forfeit extends Model
{
    protected $fillable = [
        'columbary_slot_id',
        'unit_id',
        'buyer_name',
        'buyer_email',
        'payment_status',
        'price',
        'unit_price',
        'purchase_date',
        'floor_number',
        'vault_number',
        'level_number',
        'type',
    ];

    public function columbarySlot()
    {
        return $this->belongsTo(ColumbarySlot::class, 'columbary_slot_id');
    }
}