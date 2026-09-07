<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQualification extends Model
{
    use HasFactory;

    function get_quali()
    {
        return $this->belongsTo('App\Models\Qualification', 'qualification_id');
    }
}
