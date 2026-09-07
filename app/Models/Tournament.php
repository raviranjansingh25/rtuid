<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    public function scopeAvailableForCoachWeight($query, $districtId)
    {
        return $query->where(function ($q) use ($districtId) {
            $q->where('is_district_tournament', 0)
                ->orWhere(function ($q2) use ($districtId) {
                    $q2->where('is_district_tournament', 1)
                        ->where('district_id', $districtId)
                        ->where('district_apply_open', 1)
                        ->where('coach_apply_weight_open', 1);
                });
        });
    }

    public function scopeAvailableForAthleteApply($query, $districtId)
    {
        return $query->where('is_district_tournament', 1)
            ->where('district_id', $districtId)
            ->where('district_apply_open', 1)
            ->where('athlete_apply_weight_open', 1);
    }
    
    function get_category()
    {
        return $this->belongsTo('App\Models\Category', 'category');
    }

    function get_event()
    {
        return $this->belongsTo('App\Models\Event', 'event');
    }

    function get_event_cat()
    {
        return $this->belongsTo('App\Models\EventCategory', 'event_category');
    }

    function get_waight_cat()
    {
        return $this->belongsTo('App\Models\WeightCategory', 'weight_category');
    }
}
