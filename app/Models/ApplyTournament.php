<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplyTournament extends Model
{
    use HasFactory;

    function get_waight_cat()
    {
        return $this->belongsTo('App\Models\WeightCategory', 'waight_category');
    }

    function get_user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    // function  get_event()
    // {
    //     return $this->belongsTo('App\Models\EventList','event_id');
    // }
}
