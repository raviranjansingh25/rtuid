<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    function get_category()
    {
        return $this->belongsTo('App\Models\Category', 'category');
    }

    function get_quali()
    {
        return $this->belongsTo('App\Models\Qualification', 'qualification');
    }
}
