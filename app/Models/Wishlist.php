<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    function get_doctor()
    {
        return $this->belongsTo('App\Models\User', 'doctor_id');
    }
}
