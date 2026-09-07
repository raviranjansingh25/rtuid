<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    use HasFactory;

    function get_event()
    {
        return $this->belongsTo('App\Models\Event', 'event');
    }
}
