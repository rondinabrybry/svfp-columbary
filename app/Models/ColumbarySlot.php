<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColumbarySlot extends Model
{
    use HasFactory;

    protected $fillable = ['slot_number', 'status', 'price', 'floor_number', 'vault_number', 'level_number', 'type', 'unit_num_side'];

    public function payment()
    {
        return $this->hasOne(Payment::class, 'columbary_slot_id');
    }

}
