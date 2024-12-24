<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'columbary_slot_id',
        'buyer_name',
        'buyer_email',
        'buyer_address',
        'contact_info',
        'payment_status',
        'price',
    ];

    public function columbarySlot()
    {
        return $this->belongsTo(ColumbarySlot::class);
    }

}
