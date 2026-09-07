<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    function get_doctor()
    {
        return $this->belongsTo('App\Models\User', 'vender_id');
    }

    function get_patient()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getCreatedAtAttribute($value)
    {

        return date('d-m-Y', strtotime($value));
    }
}
